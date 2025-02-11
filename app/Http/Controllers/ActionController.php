<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $action = $this->action_service->createAction('Mover',$request->zone_id,'Zone',$moveTime);

        if($action){
            $user = $this->user_service->getUser();
            $zone_name = $this->zone_service->getZone($request->zone_id)->name;
            return view('actions.wait' , compact( 'zone_name' , 'moveTime'));
        } else {
            return redirect()->route('zones.index')->with('error', "Las carreteras están cortadas. No puedes viajar a esta zona.");
        }
    }

    /**
     * Realiza la acción de explorar y recolectar recursos
     */
    public function farmZone(Request $request)
    {
        $action = $this->action_service->createAction('Recolectar',$request->zone_id,'Zone',$request->farmTime);

        if($action){
            $user = $this->user_service->getUser();
            $zone_name = $this->zone_service->getZone($request->zone_id)->name;
            /* Se calculan los recursos encontrados aunque no estén disponibles hasta que termine la acción de recolectar */
            $this->resource_service->calculateFarm($request->zone_id , $request->farmTime , $action);
            return redirect()->route('zones.index')->with('success' , "$user->name estarás explorando $zone_name durante $request->farmTime minutos.");
        } else {
            return redirect()->route('zones.index')->with('error', "Ha ocurrido algo inesperado. No puedes explorar esta zona.");
        }
    }

    /**
     * Redirige al formulario para construir un edificio
     */
    public function createBuilding(string $id)
    {
        /* Obtenemos los datos del edificio que queremos construir y sus relaciones */
        $building = $this->building_service->getBuildingWithRelations($id);

        /* Y el nivel actual del edificio */
        $actual_building_level = $this->building_service->getActualLevel($id);

        /* Calculamos el próximo nivel para saber el número de inventos que necesita */
        $building_next_level = $actual_building_level + 1;

        /* Comprobamos que el jugador tiene el número de inventos necesarios */
        /* No es necesario recoger la respuesta porque si es false, se redirige */
        $this->resource_service->checkInventionsToConstruct($building->inventionTypes , $building_next_level);
        
        /* Agrupamos los inventos del inventario por tipo y redirigimos a la vista */
        $user_inventions_by_type = $this->resource_service->getInventionsByType();

        return view('buildings.create', compact('building', 'user_inventions_by_type', 'building_next_level'));
        
        
    }

    /**
     * Realiza la acción de construir un edificio
     */
    public function storeBuilding(Request $request){

        /* Declaramos las reglas de validación de los campos del formulario */
        $rules = [
            'building_id' => 'required|exists:buildings,id',
        ];

        foreach ($request->input('inventions') as $type_id => $inventions_selected) {
            $rules["inventions.$type_id"] = "required|array|size:$request->building_level";
        }

        /* Establecemos los mensajes de error del formulario */
        /* TODO Revisar porque no son estos los mensajes que salen en el navegador cuando hay un fallo en el formulario */
        $validated = $request->validate($rules, [
            'building_id.required' => 'Por favor, selecciona un edificio válido.',
            'building_id.exists' => 'El edificio seleccionado no existe en la base de datos.',
            'inventions.required' => 'Debes seleccionar al menos un invento para cada tipo.',
            "inventions.size" => "Debes seleccionar exactamente $request->building_level inventos para cada tipo.",
        ]);

        $constructTime = $this->building_service->getConstructTime($request->building_level); 

        $action = $this->action_service->createAction('Construir',$request->building_id,'Building',$constructTime);

        /* Ahora se crea el edificio aunque no esté disponible y se borran los inventos usados para ello */

        $building_efficiency = $this->building_service->calculateEfficiencyBuilding($request->building_id, $request->building_level, $request->input('inventions'));

        $action_building = $this->action_service->createActionBuilding($action , $request->building_id , $building_efficiency);

        $this->invention_service->eliminateInventionsUsed($request->input('inventions') , $action_building->_id , 'Building');

        if($action){
            $user = $this->user_service->getUser();
            $building = $this->building_service->getBuilding($request->building_id);
            return redirect()->route('buildings.show' , $building->_id)
            ->with('success', "$user->name la construcción de $building->name durará $constructTime minutos.");
        } else {
            return redirect()->route('buildings.index')
                         ->with('error', "No se ha podido construir el edificio.");
        }
    }

    /**
     * Redirige al formulario para la acción de crear un invento
     */
    public function createInvention(string $id){

        /* Obtenemos los datos del tipo de invento con sus relaciones con tipos de material */
        $invention_type = $this->inventionType_service->getInventionTypeWithRelations($id);
        //dd($invention_type);
        /* Comprobamos que el usuario tiene los materiales necesarios para crear este invento y los recuperamos */
        $user_materials = $this->resource_service->checkMaterials($invention_type->material_type_id , $invention_type->materialType->name);

        /* Comprobamos que el usuario tiene los inventos necesarios para crear este invento */
        $this->resource_service->checkInventionsToCreate($invention_type->inventionTypes);

        /* Recuperamos los materiales e inventos que tiene el usuario */
        $user_inventions_by_type = $this->resource_service->getInventionsByType();

        return view('inventions.create', compact('invention_type', 'user_materials', 'user_inventions_by_type'));
    }


    /**
     * Guarda los datos del formulario de creación del invento
     */
    public function storeInvention (){

        /* TODO Cuidado porque hay que crear el invento antes que la acción para dar el id */
        $action = $this->action_service->createAction('Crear',$invention_id,'Invention',$request->farmTime);
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
