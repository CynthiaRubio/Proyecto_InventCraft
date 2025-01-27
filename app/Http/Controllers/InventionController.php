<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invention;
use App\Models\Material;
use App\Models\InventionType;
use App\Models\ActionType;
use App\Models\User;
use App\Models\Inventory;
use App\Models\InventoryMaterial;
use App\Models\Action;
use App\Models\InventionTypeInventionType;
use Faker\Factory as Faker;
use Illuminate\Support\Str;


class InventionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('inventions.index', ['inventions' => Invention::all()]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Aquí se mostrarán todas las características del invento, que será llamado desde la vista
        // show de tipos de inventos donde aparece el listado de todos los inventos que hay de ese tipo
        // y desde el index de inventions

        $invention = Invention::findOrFail($id);

        //$material_id = $invention->material_id;
        $material = Material::where('id', $invention->material_id)->get()->value('name'); // $material_id)->get()->value('name');

        $invention_type_id = $invention->invention_type_id;
        $invention_type = InventionType::where('id', $invention_type_id)->get()->value('name');

        return view('inventions.show', compact('invention' , 'material' , 'invention_type'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $id)
    {

        $users = User::all();
        $user = $users->random();

        /* La forma correcta de hacer lo anterior es con el usuario autenticado como se hace a continuación
            $user = auth()->user();
        */

        /* Seleccionamos el inventario del jugador y nos traemos todos sus materiales (con sus atributos) y todos sus inventos */
        $inventory = Inventory::where('user_id', $user->_id)->with(['materials.material', 'inventions'])->first();

        /* Obtenemos el tipo de invento que vamos a crear */
        $invention_type = InventionType::findOrFail($id);

        /* Si queremos los atributos del tipo de material, como el nombre podríamos hacer
        $invention_type = InventionType::with('materialType')->findOrFail($id);
        */

        /* Obtenemos los tipos de invento que necesitamos para crearlo(y cantidad de cada tipo de invento). Con el with se sacan los atributos de cada tipo de inventos */
        $invention_types_needed = InventionTypeInventionType::where('invention_type_id', $invention_type->_id)->with('inventionTypeNeed')->get();

        /* Materiales e inventos del usuario del tipo necesario (la primera linea es la buena, la comento porque de momento no hay materiales en inventario) */
        //$user_materials = $inventory->materials->where('material_type_id', $invention_type->material_type_id);
        $user_materials = Material::where('material_type_id' , $invention_type->material_type_id)->get();
        $user_invention_by_type = $inventory->inventions->groupBy('invention_type_id');

        /* Validamos si el usuario tiene materiales del tipo necesario */
            if ($user_materials->isEmpty()) {
                return redirect()->route('inventionTypes.index')->with('error', 'No tienes materiales de tipo '.$invention_type->material_type->name.' para crear este invento.');
            }
        

        /* Validamos si tiene la cantidad necesaria de cada tipo de inventos */
        foreach ($invention_types_needed as $invention) {
            // Si no tiene ningun invento de ese tipo
            if (!isset($user_invention_by_type[$invention->invention_type_need_id])) {
                return redirect()->route('inventionTypes.index')->with('error', 'No tienes inventos del tipo ' . $invention->inventionTypeNeed->name);
            }

            //Si tiene menos inventos de los que se necesitan
            $userInventions = $user_invention_by_type[$invention->invention_type_need_id];
            if ($userInventions->count() < $invention->quantity) {
                return redirect()->route('inventionTypes.index')->with('error', 'No tienes suficientes inventos del tipo ' . $invention->inventionTypeNeed->name);
            }
        }

        return view('inventions.create', compact('invention_type', 'invention_types_needed', 'user_materials', 'user_invention_by_type'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) /* TO DO REVISAR porque no elimina los inventos usados para crear otro */
    {
        $faker = Faker::create();

        /* Recuperamos los datos del usuario y de su inventario aunque así no es correcto porque tiene que ser el usuario autenticado */
        $users = User::all();
        $user_id = $users->random()->value('id');
        $inventory = Inventory::where('user_id', $user_id)->first();

        /* Guardamos los datos que nos llega en el formulario */
        $invention_type_id = $request->input('invention_type_id');
        //$invention_types_needed = $request->input('invention_types_needed');
        $material_id = $request->input('material_id');
        $selected_inventions = $request->input('inventions'); 

        /* Recuperamos los datos del material que ha sido usado en el formulario */
        $material = Material::findOrFail($material_id);

        /* Realizamos la consulta para saber los tipos de inventos que son necesarios para crear este invento */
        $invention_types_needed = InventionTypeInventionType::where('invention_type_id', $invention_type_id)->get();

        /* Establecemos las reglas de los datos de material del formulario */
        $rules = [
            'material_id' => 'required|exists:materials,id',
        ];

        /* Validamos que los datos del formulario cumplen con las reglas establecidas TO DO REVISAR porque no aplica */
        $validated = $request->validate($rules, [
            'material.required' => 'Debes seleccionar un material',
        ]);

        /* Comprobamos que la cantidad de inventos seleccionados coincida con los necesarios de cada tipo */
        // Para ello recorremos el array de tipos de inventos necesarios para realizar este invento
        foreach ($invention_types_needed as $invention_type) {
            // Y recorremos los inventos seleccionados en el formulario
            foreach($selected_inventions as $type => $inventions){
                if (count($inventions) != $invention_type->quantity) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Debes seleccionar al menos ' . $invention_type->quantity . ' invento(s) del tipo ' . $invention_type->inventionTypeNeed->name);
                }
            }
        }

        
/*
        /* Validamos la cantidad de inventos que han sido seleccionados de cada tipo
        foreach ($invention_types_needed as $typeId => $invention_needed) {
            $selected_inventions = $request->input('inventions.' . $invention_needed->invention_type_need_id, []);

            if (count($selected_inventions) < $invention_needed->quantity) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Debes seleccionar al menos ' . $invention_needed->quantity . ' invento(s) del tipo ' . $invention_needed->inventionTypeNeed->name);
            }
        }
*/
        /* Creamos el nuevo invento */
        $new_invention = Invention::create([
            'invention_type_id' => $invention_type_id,
            'material_id' => $material_id,
            'inventory_id' =>  $inventory->_id,
            'action_building_id' => null,
            'invention_created_id' => null,
            'name' => $faker->name(), // 'name' => Str::random(10),; Hay que importar: use Illuminate\Support\Str;
            'efficiency' => $material->efficiency,
            //'time' => rand(0,1000),
        ]);

        /* Consultamos el id del tipo de acción crear inventos */
        $action_type_id = ActionType::where('name' , 'Crear')->first()->id;

        /* Generamos la acción de tipo crear invento */
        Action::create([
            'user_id' => $user_id,
            'action_type_id' => $action_type_id,
            'actionable_id' => $new_invention->_id,
            'actionable_type' => Invention::class,
            'time' => now()->addMinutes(rand(60, 240)),
            'finished' => false,
            'notificacion' => true,
        ]);

        /* Eliminamos el material usado para el invento de la base de datos
            $material = InventoryMaterial::find('material_id' , $material->_id)->where('inventory_id' , $inventory->_id)->get();
            $material = InventoryMaterial::where('material_id' , $material_id)->where('inventory_id' , $inventory->_id)->get()->value('quantity');
            $new_quantity = $material - 1;
            $information = ['quantity' => $new_quantity];
            $material->update($information);
        */
        
        /* Eliminamos los inventos que han sido usados para crear este nuevo invento */
        if($request->has('inventions')){
            foreach ( $selected_inventions as $type => $array_inventions) {
            
                foreach ($array_inventions as $invention_id) {
                    /* Se actualiza el campo invention_created_id en cada invento seleccionado */
                    $invention_used = Invention::find($invention_id);
                    $information = ['invention_created_id' => $new_invention->_id];
                    $invention_used->update($information);
                    /* Se elimina el invento de la base de datos */
                    //$invention_used->delete();
                    Invention::destroy($invention_used->_id);
                }
            }
        }

        return redirect()->route('inventions.show' , $new_invention->_id)
                         ->with('success', 'Invention created successfully');

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
        $invention = Invention::findOrFail($id);
        $invention->delete();
    
        return redirect()->route('inventions.index')
            ->with('success', 'Invention deleted successfully');
    }

}
