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
        /* ESTO NO ES CORRECTO DEBERIA SER EL USUARIO LOGEADO  $userId = auth()->id(); */
        $users = User::all();
        $user = $users->random();
        $inventory = Inventory::where('user_id',$user->id)->first();

        $invention_types = InventionType::where('building_id' , $id)->get();

        $inventions_inventory = Invention::where('inventory_id', $inventory->_id)->get();

        $building = Building::find($id);

        $actual_level = ActionBuilding::where('building_id' , $id)->count();

        $level = $actual_level + 1;

        return view('buildings.create' , compact( 'invention_types', 'inventions_inventory' , 'building' , 'level'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $users = User::all();
        $user = $users->random();

        $level = $request->level;
        $building_id = $request->building_id;

        $action_type_id = ActionType::where('name' , 'Construir')->value('id')->get();

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

        //Obtenemos el id de la última acción
        $action_id = Action::latest('id')->value('id');

        //Obtenemos los inventos seleccionados en el formulario
        $inventions = $request->input('inventions'); 

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
            foreach($array_invention as $invention_id){
                $invention_used = Invention::find($invention_id);
                /* TO DO Manejar si la eficiencia es nula */
                if($invention_used->efficiency != null){
                    $efficiency += $invention_used->efficiency;
                    $num_inventions++;
                }
            }
        }

        $efficiency = ($efficiency / $num_inventions) / ($level * 2);

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

        // Eliminamos los inventos usados tras actualizarlos
        foreach($inventions as $type => $array_invention){
            foreach($array_invention as $invention_id){
                $invention_used = Invention::find($invention_id);
                $information = ['action_building_id' => $action_building_id];
                $invention_used->update($information);
                $invention_used->delete();
                //Invention::destroy($invention_used->id);
            }
        }

        return redirect()->route('buildings.show' , $building_id)
                         ->with('success', 'Building created successfully');
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
