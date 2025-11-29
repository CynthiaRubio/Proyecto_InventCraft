<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MoveZoneRequest;
use App\Http\Requests\FarmZoneRequest;
use App\Http\Requests\StoreBuildingRequest;
use App\Http\Requests\StoreInventionRequest;
use App\Contracts\ActionServiceInterface;
use App\Contracts\ResourceServiceInterface;
use App\Contracts\ZoneServiceInterface;
use App\Contracts\InventionServiceInterface;
use App\Contracts\UserServiceInterface;
use App\Contracts\BuildingServiceInterface;
use App\Contracts\InventionTypeServiceInterface;

class ActionController extends Controller
{
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
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con la acción creada o error
     */
    public function moveZone(MoveZoneRequest $request)
    {
        $moveTime = $this->zoneService->calculateMoveTime($request->zone_id);
        $action = $this->actionService->createAction('Mover', $request->zone_id, 'Zone', $moveTime);

        if ($action) {
            $user = $this->userService->getUser();
            $zone = $this->zoneService->getZone($request->zone_id);
            
            return response()->json([
                'message' => "Te estás moviendo a {$zone->name}",
                'action' => $action,
                'move_time' => $moveTime,
                'zone' => $zone,
            ], 201);
        }

        return response()->json(['error' => 'Las carreteras están cortadas. No puedes viajar a esta zona.'], 400);
    }

    /**
     * Realiza la acción de explorar y recolectar recursos en una zona.
     * 
     * Los recursos encontrados se calculan inmediatamente pero no estarán disponibles
     * hasta que finalice la acción de recolección.
     * 
     * @param FarmZoneRequest $request Solicitud validada con el ID de la zona y tiempo de exploración
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con la acción creada o error
     */
    public function farmZone(FarmZoneRequest $request)
    {
        $farmTime = (int) $request->farmTime; // Tiempo en minutos
        $action = $this->actionService->createAction('Recolectar', $request->zone_id, 'Zone', $farmTime);

        if ($action) {
            $user = $this->userService->getUser();
            $zone = $this->zoneService->getZone($request->zone_id);
            
            $this->resourceService->calculateFarm($request->zone_id, $farmTime, $action);

            return response()->json([
                'message' => "{$user->name} estarás explorando {$zone->name} durante {$farmTime} minutos.",
                'action' => $action,
                'zone' => $zone,
            ], 201);
        }

        return response()->json(['error' => 'Ha ocurrido algo inesperado. No puedes explorar esta zona.'], 400);
    }

    /**
     * Devuelve la información necesaria para construir un edificio.
     * 
     * Si es la Estación Espacial, verifica que todos los demás edificios
     * tengan eficiencia 100% antes de permitir su construcción.
     * 
     * @param string $id ID del edificio a construir
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con la información del edificio y requisitos
     */
    public function createBuilding(string $id)
    {
        $building = $this->buildingService->getBuilding($id);

        $canBuildSpaceStation = null;
        if ($building->name === 'Estación Espacial') {
            $canBuildSpaceStation = $this->buildingService->canBuildSpaceStation();
            if (!$canBuildSpaceStation['can_build']) {
                return response()->json([
                    'error' => $canBuildSpaceStation['reason'],
                    'buildings_status' => $canBuildSpaceStation['buildings_status'] ?? [],
                ], 400);
            }
        }

        $actual_building_level = $this->buildingService->getActualLevel($id);
        $building_next_level = $actual_building_level + 1;
        $invention_types_needed = $this->buildingService->getInventionTypesNeededForBuilding($id);
        $user_inventions_by_type = $this->resourceService->getUserInventionsByType();

        return response()->json([
            'building' => $building,
            'actual_level' => $actual_building_level,
            'next_level' => $building_next_level,
            'invention_types_needed' => $invention_types_needed,
            'user_inventions_by_type' => $user_inventions_by_type,
            'can_build_space_station' => $canBuildSpaceStation,
        ], 200);
    }

    /**
     * Realiza la acción de construir un edificio.
     * 
     * @param StoreBuildingRequest $request Solicitud validada con los datos de construcción
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con la acción creada o error
     */
    public function storeBuilding(StoreBuildingRequest $request)
    {
        // Validar que los inventos seleccionados sean válidos
        $invention_types_needed = $this->buildingService->getInventionTypesNeededForBuilding($request->building_id);
        
        $validation = $this->resourceService->validateSelectedInventionsForBuilding(
            $request->input('inventions', []),
            $invention_types_needed,
            (int) $request->building_level
        );

        if (!$validation['valid']) {
            return response()->json([
                'error' => $validation['error'],
            ], 422);
        }

        $time = $this->buildingService->getConstructTime((int) $request->building_level);

        $result = $this->buildingService->constructBuilding(
            $request->building_id,
            (int) $request->building_level,
            $request->input('inventions'),
            $time
        );

        if (isset($result['error'])) {
            return response()->json([
                'error' => $result['error'],
                'buildings_status' => $result['buildings_status'] ?? [],
            ], 400);
        }

        if ($result['action']) {
            $user = $this->userService->getUser();
            $building = $this->buildingService->getBuilding($request->building_id);
            
            return response()->json([
                'message' => "{$user->name} la construcción de {$building->name} durará {$result['construct_time']} minutos.",
                'action' => $result['action'],
                'building' => $building,
                'construct_time' => $result['construct_time'],
            ], 201);
        }

        return response()->json(['error' => 'No se ha podido construir el edificio.'], 400);
    }

    /**
     * Devuelve la información necesaria para crear un invento.
     * 
     * @param string $id ID del tipo de invento a crear
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con la información del tipo de invento y requisitos
     */
    public function createInvention(string $id)
    {
        $invention_type = $this->inventionTypeService->getInventionTypeWithRelations($id);
        $invention_types_needed = $this->inventionTypeService->getInventionsNeeded($id);
        $user_materials = $this->resourceService->getUserMaterialsByType((string) $invention_type->material_type_id);
        $user_inventions_by_type = $this->resourceService->getUserInventionsByType();

        return response()->json([
            'invention_type' => $invention_type,
            'invention_types_needed' => $invention_types_needed,
            'user_materials' => $user_materials,
            'user_inventions_by_type' => $user_inventions_by_type,
        ], 200);
    }

    /**
     * Realiza la acción de crear un invento.
     * 
     * @param StoreInventionRequest $request Solicitud validada con los datos del invento
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el invento creado y la acción
     */
    public function storeInvention(StoreInventionRequest $request)
    {
        // Validar que los inventos seleccionados sean válidos
        $invention_types_needed = $this->inventionTypeService->getInventionsNeeded($request->invention_type_id);
        
        $validation = $this->resourceService->validateSelectedInventionsForInvention(
            $request->input('inventions', []),
            $invention_types_needed->toArray()
        );

        if (!$validation['valid']) {
            return response()->json([
                'error' => $validation['error'],
            ], 422);
        }

        $time = (int) $request->time; // Ya está en minutos
        $inventions_used = $request->has('inventions') ? $request->input('inventions') : null;
        
        $result = $this->inventionService->createInventionWithAction(
            $request->invention_type_id,
            $request->material_id,
            $time,
            $inventions_used
        );

        return response()->json([
            'message' => "Invento {$result['invention']->name} en creación.",
            'invention' => $result['invention'],
            'action' => $result['action'],
        ], 201);
    }
}

