<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Action;
use App\Models\Building;
use App\Models\ActionBuilding;
use App\Models\InventionType;
use App\Models\Inventory;
use App\Models\Invention;
use App\Models\User;
use App\Models\ActionType;

class ActionBuildingController extends Controller
{
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
    public function create(string $id)
    {
        $user = auth()->user();

        $inventory = Inventory::where('user_id',$user->id)->first();

        $invention_types = InventionType::where('building_id' , $id)->get();

        $inventions_inventory = Invention::where('inventory_id', $inventory->_id)->get();

        $building = Building::find($id);

        $actual_level = Action::where('user_id', $user->_id)->where('actionable_id', $building->_id)->count();

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

        return view('buildings.create' , compact( 'invention_types', 'inventions_inventory' , 'building' , 'level'));
    }

    // public function create(string $id)
    // {
    //     $user = auth()->user();
    //     $inventory = Inventory::with('inventions')->where('user_id', $user->id)->firstOrFail();
    //     $building = Building::findOrFail($id);
    //     $invention_types = InventionType::where('building_id', $id)->get();
    //     $user_inventions_by_type = $inventory->inventions->groupBy('invention_type_id');

    //     $level = ActionBuilding::where('building_id', $id)->count() + 1;    //nivel al que mejorará

    //     // Validar si el usuario tiene suficientes inventos
    //     foreach ($invention_types as $type) {
    //         if (!isset($user_inventions_by_type[$type->id]) || count($user_inventions_by_type[$type->id]) < $level) {
    //             return redirect()->route('buildings.index')->with(
    //                 'error',
    //                 "No tienes suficientes inventos de tipo {$type->name}. Se requieren {$level}."
    //             );
    //         }
    //     }
    //     return view('buildings.create', compact('invention_types', 'user_inventions_by_type', 'building', 'level'));
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $level = $request->level;
        $building_id = $request->building_id;

        $action_type_id = ActionType::where('name' , 'Construir')->first()->id;

        //Tenemos que crear una accion de tipo construir para usar su id
        Action::create([
            'user_id' => $user->_id,
            'action_type_id' => $action_type_id,
            'actionable_id' => $building_id,
            'actionable_type' => Building::class,
            'time' => now()->addMinutes(rand(60, 240)),
            'finished' => false,
            'notificacion' => true,
        ]);

        //Obtenemos el id de la última acción de construir
        $action_id = Action::where('user_id', $user->_id)->where('action_type_id' , $action_type_id)->where('actionable_id', $building_id)->latest()->value('id');

        //Obtenemos los inventos seleccionados en el formulario, si no existen, se declara un array vacio
        $inventions = $request->input('inventions' , []); 

        //Declaramos las reglas de validación
        $rules = [
            'building_id' => 'required|exists:buildings,id',
        ];

        foreach ($inventions as $typeId => $selectedInventions) {
            $rules["inventions.$typeId"] = "required|array|size:$level";
        }

        // Validamos la entrada del formulario
        $validated = $request->validate($rules, [
            'building_id.required' => 'Por favor, selecciona un edificio válido.',
            'building_id.exists' => 'El edificio seleccionado no existe en la base de datos.',
            'inventions.required' => 'Debes seleccionar al menos un invento para cada tipo.',
            'inventions.size' => "Debes seleccionar exactamente $level inventos para cada tipo.",
        ]);

        //Los inventos los necesitamos para el calculo de la eficiencia
        $efficiency = 0;
        $num_inventions = 0;

        foreach($inventions as $type => $array_invention){
            if(!empty($array_invention)){
                $inventions_used = Invention::whereIn( 'id' , $array_invention)->get();
                foreach($inventions_used as $invention){
                    if($invention->efficiency != null){
                        $efficiency += $invention->efficiency;
                        $num_inventions++;
                    }
                }
            }
        }

        if($num_inventions !== 0){
            $efficiency = ($efficiency / $num_inventions) / ($level * 2);
        }

        if($level > 1){
            $actual_efficiency = ActionBuilding::where('building_id' , $building_id)->latest('id')->value('efficiency');
            $efficiency += $actual_efficiency;
        }

        // Crear el registro de construir el edificio
        ActionBuilding::create([
            'action_id' => $action_id,
            'building_id' => $validated['building_id'],
            'efficiency' => $efficiency,
        ]);

        $action_building_id = ActionBuilding::latest('id')->value('id');

        // Actualizamos el bloque de inventos usados y los eliminamos
        foreach($inventions as $type => $array_inventions){
            if(!empty($array_inventions)){
                $information = ['action_building_id' => $action_building_id];
                Invention::whereIn( 'id' , $array_inventions)->update($information);
                Invention::whereIn( 'id' , $array_inventions)->delete();
                //Invention::destroy($invention_used->id);
            }
        }

        return redirect()->route('buildings.show' , $building_id)
                         ->with('success', "$user->name has creado este edificio satisfactoriamente.");
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
