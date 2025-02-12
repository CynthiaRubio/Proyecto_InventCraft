<?php

namespace App\Http\Controllers;

use App\Models\InventionType;
use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\Action;
use App\Models\ActionBuilding;
use App\Models\Invention;
use App\Models\User;
use App\Models\ActionType;
use App\Services\UserManagementService;
use App\Services\ActionManagementService;
class ActionBuildingController extends Controller
{
    public function __construct(private UserManagementService $user_service, private ActionManagementService $action_service)
    {
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
    public function createBuilding(string $id)
    {

        $inventory = $this->user_service->getUserInventoryWithRelations();
        $building = Building::findOrFail($id);
        $invention_types = InventionType::where('building_id', $id)->get();
        $user_inventions_by_type = $inventory->inventions->groupBy('invention_type_id');

        $actual_level = Action::where('user_id', auth()->user()->id)->where('actionable_id', $building->_id)->count();
        $level = $actual_level + 1;

        // Validar si el usuario tiene suficientes inventos
        foreach ($invention_types as $type) {
            if (!isset($user_inventions_by_type[$type->id]) || count($user_inventions_by_type[$type->id]) < $level) {
                return redirect()->route('buildings.index')->with(
                    'error',
                    "No tienes suficientes inventos de tipo {$type->name}. Se requieren {$level}."
                );
            }
        }
        return view('buildings.create', compact('invention_types', 'user_inventions_by_type', 'building', 'level'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Hecho
        $user = auth()->user();

        $building_id = $request->building_id;
        $building_level = $request->level;

        //Hecho
        $action_type_id = ActionType::where('name', 'Construir')->first()->id;

        //Hecho
        $time = (600 / $user->level) * $building_level;

        //Tenemos que crear una accion de tipo construir para usar su id
        //Hecho
        $action_id = $this->action_service->createAction('Construir', $building_id, 'Building', 10); //cambiar a time

        //Obtenemos los inventos seleccionados en el formulario, si no existen, se declara un array vacio
        $inventions = $request->input('inventions', []);

        //Hecho
        $rules = [
            'building_id' => 'required|exists:buildings,id',
            'inventions' => 'required|array',
        ];

        foreach ($inventions as $typeId => $selectedInventions) {
            $rules["inventions.$typeId"] = "required|array|size:$building_level|exists:inventions,id";
        }

        // Validar la entrada del formulario
        $validated = $request->validate($rules, [
            'building_id.required' => 'Por favor, selecciona un edificio válido.',
            'building_id.exists' => 'El edificio seleccionado no existe en la base de datos.',
            'inventions.*.required' => 'Debes seleccionar al menos un invento para cada tipo.',
            'inventions.*.size' => "Debes seleccionar exactamente $building_level inventos para cada tipo.",
        ]);
        

        //Los inventos los necesitamos para el calculo de la eficiencia
        $efficiency = 0;
        $total_invents = 0;

        foreach ($inventions as $type => $array_invention) {
            if (!empty($array_invention)) {
                $inventions_used = Invention::whereIn('id', $array_invention)->get();
                foreach ($inventions_used as $invention) {
                    if ($invention->efficiency != null) {
                        $efficiency += $invention->efficiency;
                        $total_invents++;
                    }
                }
            }
        }

        if ($total_invents !== 0) {
            $efficiency = ($efficiency / $total_invents) / ($building_level * 2);
        }


        if ($building_level > 1) {
            $actual_efficiency = ActionBuilding::where('building_id', $building_id)->latest('id')->value('efficiency');
            $efficiency += $actual_efficiency;
        }

        // Crear el registro de acción de construcción
        $action_building = ActionBuilding::create([
            'action_id' => $action_id,
            'building_id' => $request->building_id,
            'efficiency' => $efficiency,
        ]);

        // Actualizamos el bloque de inventos usados y los eliminamos
        foreach ($inventions as $type => $arrayInvention) {
            foreach ($arrayInvention as $inventionId) {

                if (!empty($array_inventions)) {
                    $information = ['action_building_id' => $action_building->id];
                    Invention::whereIn('id', $array_inventions)->update($information);
                    Invention::whereIn('id', $array_inventions)->delete();
                    //Invention::destroy($invention_used->id);
                }
            }
        }
        //Hasta aqui
        $building = Building::findOrFail($building_id)->name;


        return redirect()->route('buildings.show', $building_id)
            ->with('success', 'Estás construyendo el edificio '.$building.'.');
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
