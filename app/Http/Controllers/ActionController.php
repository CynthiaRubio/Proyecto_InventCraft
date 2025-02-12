<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventionType;
use App\Services\ActionManagementService;
use App\Services\ResourceManagementService;
use App\Services\ZoneManagementService;
use App\Services\InventionService;
use App\Services\UserManagementService;
use App\Services\BuildingManagementService;
use App\Services\InventionTypeService;

class ActionController extends Controller
{
    public function __construct(
        private ActionManagementService $action_service,
        private ResourceManagementService $resource_service,
        private ZoneManagementService $zone_service,
        private InventionService $invention_service,
        private UserManagementService $user_service,
        private BuildingManagementService $building_service,
        private InventionTypeService $inventionType_service,
    ) {
    }

    /**
     * Realiza la acción de desplazarse a otra zona
    */
    public function moveZone(Request $request)
    {
        $moveTime = $this->zone_service->calculateMoveTime($request->zone_id);
        
        $moveTime = 8;

        $action = $this->action_service->createAction('Mover', $request->zone_id, 'Zone', $moveTime);

        if ($action) {
            $user = $this->user_service->getUser();
            $zone_name = $this->zone_service->getZone($request->zone_id)->name;
            return view('actions.wait', compact('zone_name', 'moveTime'));
        } else {
            return redirect()->route('zones.index')->with('error', "Las carreteras están cortadas. No puedes viajar a esta zona.");
        }
    }

    /**
     * Realiza la acción de explorar y recolectar recursos
     */
    public function farmZone(Request $request)
    {
        /* Harcodeamos el tiempo para el video */
        $action = $this->action_service->createAction('Recolectar', $request->zone_id, 'Zone', 5);//$request->farmTime);

        if ($action) {
            $user = $this->user_service->getUser();
            $zone_name = $this->zone_service->getZone($request->zone_id)->name;
            /* Se calculan los recursos encontrados aunque no estén disponibles hasta que termine la acción de recolectar */
            $this->resource_service->calculateFarm($request->zone_id, $request->farmTime, $action);
            return redirect()->route('zones.index')->with('success', "$user->name estarás explorando $zone_name durante $request->farmTime minutos.");
        } else {
            return redirect()->route('zones.index')->with('error', "Ha ocurrido algo inesperado. No puedes explorar esta zona.");
        }
    }

    /**
     * Redirige al formulario para construir un edificio
     */
    public function createBuilding(string $id)
    {
        /* Obtenemos los datos del edificio que queremos construir */
        $building = $this->building_service->getBuilding($id);

        /* Y el nivel actual del edificio */
        $actual_building_level = $this->building_service->getActualLevel($id);

        /* Calculamos el próximo nivel para saber el número de inventos que necesita */
        $building_next_level = $actual_building_level + 1;

        /* Obtenemos los tipos de inventos que el edificio necesita para ser construido */
        $invention_types_needed = InventionType::where('building_id', $id)->get();

        /* Agrupamos los inventos del inventario por tipo */
        $user_inventions_by_type = $this->resource_service->getUserInventionsByType();

        /* Comprobamos que el jugador tiene el número de inventos necesarios */
        /* No es necesario recoger la respuesta porque si es false, se redirige */
        $this->resource_service->checkInventionsToConstruct($invention_types_needed, $building_next_level, $user_inventions_by_type);

        return view('buildings.create', compact('building', 'building_next_level', 'user_inventions_by_type', 'invention_types_needed'));


    }

    /**
     * Realiza la acción de construir un edificio
     */
    public function storeBuilding(Request $request)
    {

        /* Declaramos las reglas de validación de los campos del formulario */
        $rules = [
            'building_id' => 'required|exists:buildings,id',
            'inventions' => 'required|array',
        ];

        foreach ($request->input('inventions') as $type_id => $inventions_selected) {
            $rules["inventions.$type_id"] = "required|array|size:$request->building_level|exists:inventions,id";
        }

        /* Establecemos los mensajes de error del formulario */
        $validated = $request->validate($rules, [
            'building_id.required' => 'Por favor, selecciona un edificio válido.',
            'building_id.exists' => 'El edificio seleccionado no existe en la base de datos.',
            'inventions.*.required' => 'Debes seleccionar al menos un invento para cada tipo.',
            "inventions.*.size" => "Debes seleccionar exactamente $request->building_level inventos para cada tipo.",
        ]);

        $constructTime = $this->building_service->getConstructTime($request->building_level);

        /* Harcodeamos el tiempo para el video */
        $action = $this->action_service->createAction('Construir', $request->building_id, 'Building', 10);//$constructTime);

        /* Ahora se crea el edificio aunque no esté disponible y se borran los inventos usados para ello */

        $building_efficiency = $this->building_service->calculateEfficiencyBuilding($request->building_id, $request->building_level, $request->input('inventions'));

        $action_building = $this->action_service->createActionBuilding($action, $request->building_id, $building_efficiency);

        $this->invention_service->eliminateInventionsUsed($request->input('inventions'), $action_building->_id, 'Building');

        if ($action) {
            $user = $this->user_service->getUser();
            $building = $this->building_service->getBuilding($request->building_id);
            return redirect()->route('buildings.show', $building->_id)
            ->with('success', "$user->name la construcción de $building->name durará $constructTime minutos.");
        } else {
            return redirect()->route('buildings.index')
                         ->with('error', "No se ha podido construir el edificio.");
        }
    }

    /**
     * Redirige al formulario para la acción de crear un invento
     */
    public function createInvention(string $id)
    {

        /* Obtenemos los datos del tipo de invento con la relacion con tipos de material */
        $invention_type = $this->inventionType_service->getInventionTypeWithRelations($id);

        /* Obtenemos los tipos de inventos (y la cantidad) que necesita el invento */
        $invention_types_needed = $this->inventionType_service->getInventionsNeeded($id);

        /* Recuperamos los materiales  que tiene el usuario */
        $user_materials = $this->resource_service->getUserMaterialsByType($invention_type->material_type_id);

        /* Recuperamos los inventos  que tiene el usuario */
        $user_inventions_by_type = $this->resource_service->getUserInventionsByType();

        /* Comprobamos que el usuario tiene los materiales necesarios para crear este invento y los recuperamos */
        $this->resource_service->checkMaterials($user_materials, $invention_type->materialType->name);

        /* Comprobamos que el usuario tiene los inventos necesarios para crear este invento */
        $this->resource_service->checkInventionsToCreate($invention_types_needed, $user_inventions_by_type);

        return view('inventions.create', compact('invention_type', 'user_materials', 'user_inventions_by_type', 'invention_types_needed'));
    }


    /**
     * Guarda los datos del formulario de creación del invento
     */
    public function storeInvention(Request $request)
    {

        /* Establecemos las reglas de los datos de material del formulario */
        $rules = [
            'material_id' => 'required|exists:materials,id',
        ];

        /* Validamos que se selecciona un material */
        $validated = $request->validate($rules, [
            'material.required' => 'Debes seleccionar un material',
        ]);

        /* Obtenemos los tipos de inventos necesarios para su creación */
        $invention_types_needed = $this->inventionType_service->getInventionsNeeded($request->input('invention_type_id'));

        /* Validamos el número de inventos seleccionados en el formulario */
        foreach ($invention_types_needed as $needed) {
            $selected_inventions = $request->input('inventions.' . $needed->invention_type_need_id, []);
            if (count($selected_inventions) !== $needed->quantity) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Debes seleccionar exactamente ' . $needed->quantity . ' invento(s) del tipo ' . $needed->inventionTypeNeed->name);
            }
        }

        /* Se debe crear el invento antes de crear la acción porque necesitamos el id del invento */
        $new_invention = $this->invention_service->createInvention($request->invention_type_id, $request->material_id, $request->time);

        /* Se realiza la acción de crear invento */
        /* Harcodeamos el tiempo para el video */
        $action = $this->action_service->createAction('Crear', $new_invention->id, 'Invention', 5);//$request->time);

        /* Se eliminan los recursos utilizados para la creación del invento */
        $this->resource_service->decrementMaterial($request->material_id);
        if($request->has('inventions')){
            $this->invention_service->eliminateInventionsUsed($request->input('inventions'), $new_invention->_id, 'Invention');
        }

        return redirect()->route('inventionTypes.index')
             ->with('success', "Invento $new_invention->name en creación.");

    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

}
