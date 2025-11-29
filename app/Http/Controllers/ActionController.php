<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\ActionServiceInterface;
use App\Contracts\ResourceServiceInterface;
use App\Contracts\ZoneServiceInterface;
use App\Contracts\InventionServiceInterface;
use App\Contracts\UserServiceInterface;
use App\Contracts\BuildingServiceInterface;
use App\Contracts\InventionTypeServiceInterface;
use App\Http\Requests\StoreBuildingRequest;
use App\Http\Requests\StoreInventionRequest;
use App\Http\Requests\MoveZoneRequest;
use App\Http\Requests\FarmZoneRequest;
use App\ViewModels\BuildingCreateViewModel;

class ActionController extends Controller
{
    /**
     * Constructor del controlador.
     * 
     * @param ActionServiceInterface $actionService Servicio de acciones
     * @param ResourceServiceInterface $resourceService Servicio de recursos
     * @param ZoneServiceInterface $zoneService Servicio de zonas
     * @param InventionServiceInterface $inventionService Servicio de inventos
     * @param UserServiceInterface $userService Servicio de usuarios
     * @param BuildingServiceInterface $buildingService Servicio de edificios
     * @param InventionTypeServiceInterface $inventionTypeService Servicio de tipos de inventos
     */
    public function __construct(
        private ActionServiceInterface $actionService,
        private ResourceServiceInterface $resourceService,
        private ZoneServiceInterface $zoneService,
        private InventionServiceInterface $inventionService,
        private UserServiceInterface $userService,
        private BuildingServiceInterface $buildingService,
        private InventionTypeServiceInterface $inventionTypeService,
    ) {
    }

    /**
     * Realiza la acción de desplazarse a otra zona.
     * 
     * @param MoveZoneRequest $request Solicitud validada con el ID de la zona
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse Vista de espera o redirección con error
     */
    public function moveZone(MoveZoneRequest $request)
    {
        $moveTime = $this->zoneService->calculateMoveTime($request->zone_id);

        $action = $this->actionService->createAction('Mover', $request->zone_id, 'Zone', $moveTime);

        if ($action) {
            $user = $this->userService->getUser();
            $zone_name = $this->zoneService->getZone($request->zone_id)->name;
            return view('actions.wait', compact('zone_name', 'moveTime'));
        } else {
            return redirect()->route('zones.index')->with('error', "Las carreteras están cortadas. No puedes viajar a esta zona.");
        }
    }

    /**
     * Realiza la acción de explorar y recolectar recursos en una zona.
     * 
     * Los recursos encontrados se calculan inmediatamente pero no estarán disponibles
     * hasta que finalice la acción de recolección.
     * 
     * @param FarmZoneRequest $request Solicitud validada con el ID de la zona y tiempo de exploración
     * @return \Illuminate\Http\RedirectResponse Redirección al mapa con mensaje de éxito o error
     */
    public function farmZone(FarmZoneRequest $request)
    {
        $farmTime = (int) $request->farmTime;

        $action = $this->actionService->createAction('Recolectar', $request->zone_id, 'Zone', $farmTime);

        if ($action) {
            $user = $this->userService->getUser();
            $zone_name = $this->zoneService->getZone($request->zone_id)->name;
            $this->resourceService->calculateFarm($request->zone_id, $farmTime, $action);
            return redirect()->route('zones.index')->with('success', "$user->name estarás explorando $zone_name durante $request->farmTime minutos.");
        } else {
            return redirect()->route('zones.index')->with('error', "Ha ocurrido algo inesperado. No puedes explorar esta zona.");
        }
    }

    /**
     * Muestra el formulario para construir un edificio.
     * 
     * Verifica los requisitos para construir el edificio, incluyendo:
     * - Si es la Estación Espacial, verifica que todos los demás edificios tengan eficiencia 100%
     * - Verifica que el edificio no tenga ya eficiencia máxima (100%)
     * - Comprueba que el jugador tiene los inventos necesarios
     * 
     * @param string $id ID del edificio a construir
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse Vista del formulario o redirección con error
     */
    public function createBuilding(string $id)
    {
        $building = $this->buildingService->getBuilding($id);

        $canBuildSpaceStation = null;
        if ($building->name === 'Estación Espacial') {
            $canBuildSpaceStation = $this->buildingService->canBuildSpaceStation();
            if (!$canBuildSpaceStation['can_build']) {
                return redirect()->route('buildings.show', $id)
                    ->with('error', $canBuildSpaceStation['reason'])
                    ->with('buildings_status', $canBuildSpaceStation['buildings_status']);
            }
        }

        $actual_building_level = $this->buildingService->getActualLevel($id);
        
        $actual_efficiency = $this->buildingService->getEfficiency($id);
        if ($actual_efficiency >= 100 && $actual_building_level > 0) {
            return redirect()->route('buildings.show', $id)
                ->with('error', "Este edificio ya tiene eficiencia máxima (100%). No se puede mejorar más.");
        }

        $building_next_level = $actual_building_level + 1;
        $invention_types_needed = $this->buildingService->getInventionTypesNeededForBuilding($id);
        $user_inventions_by_type = $this->resourceService->getUserInventionsByType();

        $this->resourceService->checkInventionsToConstruct($invention_types_needed, $building_next_level, $user_inventions_by_type);

        $viewModel = new BuildingCreateViewModel(
            building: $building,
            buildingNextLevel: $building_next_level,
            userInventionsByType: $user_inventions_by_type,
            inventionTypesNeeded: $invention_types_needed,
            canBuildSpaceStation: $canBuildSpaceStation,
        );

        return view('buildings.create', compact('viewModel', 'building', 'building_next_level', 'user_inventions_by_type', 'invention_types_needed', 'canBuildSpaceStation'));


    }

    /**
     * Realiza la acción de construir un edificio.
     * 
     * Valida los inventos seleccionados y construye el edificio creando una acción,
     * calculando la eficiencia, creando el registro ActionBuilding y eliminando los inventos usados.
     * 
     * @param StoreBuildingRequest $request Solicitud validada con los datos de construcción
     * @return \Illuminate\Http\RedirectResponse Redirección a los detalles del edificio o con error
     */
    public function storeBuilding(StoreBuildingRequest $request)
    {
        $invention_types_needed = $this->buildingService->getInventionTypesNeededForBuilding($request->building_id);
        
        $validation = $this->resourceService->validateSelectedInventionsForBuilding(
            $request->input('inventions', []),
            $invention_types_needed,
            (int) $request->building_level
        );

        if (!$validation['valid']) {
            return redirect()->back()
                ->withInput()
                ->with('error', $validation['error']);
        }

        $time = (int) $this->buildingService->getConstructTime((int) $request->building_level);

        $result = $this->buildingService->constructBuilding(
            $request->building_id,
            (int) $request->building_level,
            $request->input('inventions'),
            $time
        );

        if (isset($result['error'])) {
            $buildingsStatus = $result['buildings_status'] ?? [];
            return redirect()->back()
                ->withInput()
                ->with('error', $result['error'])
                ->with('buildings_status', $buildingsStatus);
        }

        if ($result['action']) {
            $user = $this->userService->getUser();
            $building = $this->buildingService->getBuilding($request->building_id);
            return redirect()->route('buildings.show', $building->id)
                ->with('success', "$user->name la construcción de $building->name durará {$result['construct_time']} minutos.");
        } else {
            return redirect()->route('buildings.index')
                ->with('error', "No se ha podido construir el edificio.");
        }
    }

    /**
     * Muestra el formulario para crear un invento.
     * 
     * Verifica que el usuario tiene los materiales e inventos necesarios
     * para crear el tipo de invento solicitado.
     * 
     * @param string $id ID del tipo de invento a crear
     * @return \Illuminate\View\View Vista del formulario de creación
     */
    public function createInvention(string $id)
    {
        $invention_type = $this->inventionTypeService->getInventionTypeWithRelations($id);
        $invention_types_needed = $this->inventionTypeService->getInventionsNeeded($id);
        $user_materials = $this->resourceService->getUserMaterialsByType((string) $invention_type->material_type_id);
        $user_inventions_by_type = $this->resourceService->getUserInventionsByType();

        $materialsCheck = $this->resourceService->checkMaterials($user_materials, $invention_type->materialType->name);
        if ($materialsCheck instanceof \Illuminate\Http\RedirectResponse) {
            return $materialsCheck;
        }

        $inventionsCheck = $this->resourceService->checkInventionsToCreate($invention_types_needed, $user_inventions_by_type);
        if ($inventionsCheck instanceof \Illuminate\Http\RedirectResponse) {
            return $inventionsCheck;
        }

        return view('inventions.create', compact('invention_type', 'user_materials', 'user_inventions_by_type', 'invention_types_needed'));
    }


    /**
     * Guarda los datos del formulario de creación del invento y crea la acción.
     * 
     * Valida los inventos seleccionados y crea el invento completo: crea el invento,
     * crea la acción, decrementa los materiales y elimina los inventos usados.
     * 
     * @param StoreInventionRequest $request Solicitud validada con los datos del invento
     * @return \Illuminate\Http\RedirectResponse Redirección a la lista de tipos de inventos con mensaje de éxito
     */
    public function storeInvention(StoreInventionRequest $request)
    {
        $invention_types_needed = $this->inventionTypeService->getInventionsNeeded($request->invention_type_id);

        $validation = $this->resourceService->validateSelectedInventionsForInvention(
            $request->input('inventions', []),
            $invention_types_needed
        );

        if (!$validation['valid']) {
            return redirect()->back()
                ->withInput()
                ->with('error', $validation['error']);
        }

        $time = (int) $request->time;
        $inventions_used = $request->has('inventions') ? $request->input('inventions') : null;
        
        $result = $this->inventionService->createInventionWithAction(
            $request->invention_type_id,
            $request->material_id,
            $time,
            $inventions_used
        );

        return redirect()->route('inventionTypes.index')
            ->with('success', "Invento {$result['invention']->name} en creación.");
    }

}
