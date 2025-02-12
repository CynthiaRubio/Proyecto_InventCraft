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
use App\Services\ActionManagementService;
use App\Services\UserManagementService;
use App\Services\InventionService;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class InventionController extends Controller
{
    public function __construct(
        private UserManagementService $user_service,
        private ActionManagementService $action_service,
        private InventionService $invention_service,
    ) {
    }

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
        /* Mediante las relaciones de las tablas recuperamos la información del material, de tipo de invento y edifcio asociado al invento */
        $invention = Invention::with(['material' , 'inventionType.building'])->findOrFail($id);

        return view('inventions.show', compact('invention'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $id)
    {
        $user = auth()->user();

        //el inventario tendrá que ser el del propio usuario, lo de arriba, esta linea habra que quitarla
         $inventory = Inventory::where('user_id', $user->_id)
         ->with(['materials.material', 'inventions'])
         ->first();

        //tipo de invento que vamos a crear. Pongo el with para poder sacar el tipo de material necesario
        //Hecho
        $invention_type = InventionType::with('materialType')->findOrFail($id);

        //tipos de invento que necesitamos para crearlo(y cantidad de cada tipo de invento). Pongo el with para poder sacar el nombre del tipo de invento
        //Hecho
        $invention_types_needed = InventionTypeInventionType::where('invention_type_id', $id)->with('inventionTypeNeed')->get();

        // Materiales e inventos del usuario del tipo necesario (la primera linea es la buena, la comento porque de momento no hay materiales en inventario)
        //Hecho
        $user_materials = $inventory->materials->where('material.material_type_id', $invention_type->material_type_id);
        // $user_materials = Material::all();
        //hecho
        $user_invention_by_type = $inventory->inventions->groupBy('invention_type_id');

        //Validamos si tiene materiales del tipo necesario. Lo dejo comentado porque de momento no tenemos materiales, pero funciona bien
        //hecho
        if ($user_materials->isEmpty()) {
            return redirect()->route('inventionTypes.index')->with('error', 'No tienes materiales de tipo '.$invention_type->materialType->name.' para crear este invento.');
        }

        //Validamos si tiene la cantidad necesaria de cada tipo de inventos
        //hecho
        foreach ($invention_types_needed as $needed) {
            // Si no tiene ningun invento de ese tipo
            if (!isset($user_invention_by_type[$needed->invention_type_need_id])) {
                return redirect()->route('inventionTypes.index')->with('error', 'No tienes inventos del tipo ' . $needed->inventionTypeNeed->name);
            }

            //Si tiene menos inventos de los que se necesitan
            $userInventions = $user_invention_by_type[$needed->invention_type_need_id];
            if ($userInventions->count() < $needed->quantity) {
                return redirect()->route('inventionTypes.index')->with('error', 'No tienes suficientes inventos del tipo ' . $needed->inventionTypeNeed->name);
            }
        }

        return view('inventions.create', compact('invention_type', 'invention_types_needed', 'user_materials', 'user_invention_by_type'));

        /* Seleccionamos el inventario del jugador y nos traemos todos sus materiales (con sus atributos) y todos sus inventos con su tipo de invento */
        $inventory = $user->with(['inventory.materials.material', 'inventory.inventions.inventionType'])->first(); //load();

        /* Obtenemos el tipo de invento que vamos a crear */
        $invention_type = InventionType::findOrFail($id);

        /* Si queremos los atributos del tipo de material, como el nombre podríamos hacer
        $invention_type = InventionType::with('materialType')->findOrFail($id);
        */

        /* Obtenemos los tipos de invento que necesitamos para crearlo(y cantidad de cada tipo de invento). Con el with se sacan los atributos de cada tipo de inventos */
        $invention_types_needed = InventionTypeInventionType::where('invention_type_id', $invention_type->_id)->with('inventionTypeNeed')->get();

        /* Materiales e inventos del usuario del tipo necesario (la primera linea es la buena, la comento porque de momento no hay materiales en inventario) */
        //$user_materials = $inventory->materials->where('material_type_id', $invention_type->material_type_id);
        //$user_materials = Material::where('material_type_id', $invention_type->material_type_id)->get();
        $user_invention_by_type = $inventory->inventions->groupBy('invention_type_id');

        /* Validamos si el usuario tiene materiales del tipo necesario */
        // if ($user_materials->isEmpty()) {
        //     return redirect()->route('inventionTypes.index')->with('error', 'No tienes materiales de tipo '.$invention_type->material_type->name.' para crear este invento.');
        // }


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
        $user = auth()->user();

        $inventory = Inventory::where('user_id', $user->_id)->first();

        /* Establecemos las reglas de los datos de material del formulario */
        //Hecho
        $rules = [
            'material_id' => 'required|exists:materials,id',
        ];

        /* Validamos que los datos del formulario cumplen con las reglas establecidas TO DO REVISAR porque no aplica */
        $validated = $request->validate($rules, [
            'material.required' => 'Debes seleccionar un material',
        ]);
        

        //tipo de invetno y tipos de inventos necesarios
        $invention_type_id = $request->input('invention_type_id');
        $invention_types_needed = InventionTypeInventionType::where('invention_type_id', $invention_type_id)->get();
        // Validación de la cantidad de inventos seleccionados
        foreach ($invention_types_needed as $needed) {
            $selectedInventions = $request->input('inventions.' . $needed->invention_type_need_id, []);

            if (count($selectedInventions) < $needed->quantity) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Debes seleccionar al menos ' . $needed->quantity . ' invento(s) del tipo ' . $needed->inventionTypeNeed->name);
            }
        }

//Hasta aqui
        $material_id = $request->input('material_id');
        
        //Crear invento
        $new_invention = $this->invention_service->createInvention($invention_type_id , $material_id , $request->time);

        


        //Eliminamos los inventos usados (cambiando antes el campo 'invention_created_id). Tbn eliminamos el material
        if ($request->has('inventions')) {
            foreach ($request->input('inventions') as $inventionTypeId => $selectedInventions) {
                foreach ($selectedInventions as $invention_id) {
                    // Actualizar el campo invention_created_id en cada invento seleccionado
                    Invention::where('id', $invention_id)->update([
                        'invention_created_id' => $new_invention->id
                    ]);

                    Invention::find($invention_id)->delete();
                }
            }
        }


        $inventoryMaterial = InventoryMaterial::where('inventory_id' , $inventory->_id)
                            ->where('material_id', $material_id)->first();
/* ESTO PASARIA A LA FUNCION DECREMENT MATERIAL QUE NO ESTA TERMINADA */
        if ($inventoryMaterial->quantity > 1) {
            $inventoryMaterial->decrement('quantity', 1);
            $inventoryMaterial->refresh(); // Recargar el modelo desde la base de datos
        }

        if ($inventoryMaterial->quantity <= 1) {
            $inventoryMaterial->delete();
        }
        /*
                     if ($inventoryMaterial->quantity > 1) {
                        $quantity = $inventoryMaterial->quantity;
                        $new_quantity = $quantity - 1;
                        $inventoryMaterial->update(['quantity' => $new_quantity]);
                     } else {
                        $inventoryMaterial->delete();
                     }
        */

        /* Creamos la acción de crear invento mediante el service */
        $this->action_service->createAction('Crear', $new_invention->id, 'Invention', $request->time);

        return redirect()->route('inventionTypes.index')
             ->with('success', "Invento $new_invention->name en creación.");

        /* Guardamos los datos que nos llega en el formulario */
        $material_id = $request->input('material_id');
        $invention_type_id = $request->input('invention_type_id');
        $selected_inventions = $request->input('inventions');

        /* Realizamos la consulta para obtener los datos del material que ha sido usado en el formulario */
        $material = Material::findOrFail($material_id);

        /* Realizamos la consulta para saber los tipos de inventos que son necesarios para crear este invento */
        $invention_types_needed = InventionTypeInventionType::where('invention_type_id', $invention_type_id)->get();

        /*
                /* Comprobamos que la cantidad de inventos seleccionados coincida con los necesarios de cada tipo
                // Para ello recorremos el array de tipos de inventos necesarios para realizar este invento
                foreach ($invention_types_needed as $invention_type) {
                    //Y guardamos el numero de inventos que nos han llegado en el formulario de cada tipo
                    $selected_inventions_count = count($selected_inventions[$invention_type->invention_type_need_id]);
                    //Si se ha seleccionado una cantidad menor, se redirige de vuelta al formulario
                    if ($selected_inventions_count < $invention_type->quantity) {
                        return redirect()->back()
                            ->withInput()
                            ->with('error', 'Debes seleccionar al menos ' . $invention_type->quantity . ' invento(s) del tipo ' . $invention_type->inventionTypeNeed->name);
                    }
                }
        */

        // Validación de la cantidad de inventos seleccionados
        foreach ($invention_types_needed as $needed) {
            $selectedInventions = $request->input('inventions.' . $needed->invention_type_need_id, []);
            if (count($selectedInventions) !== $needed->quantity) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Debes seleccionar exactamente ' . $needed->quantity . ' invento(s) del tipo ' . $needed->inventionTypeNeed->name);
            }
        }

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

        /* Eliminamos el material usado para el invento de la base de datos
            $material = InventoryMaterial::find('material_id' , $material->_id)->where('inventory_id' , $inventory->_id)->get();
            $material = InventoryMaterial::where('material_id' , $material_id)->where('inventory_id' , $inventory->_id)->get()->value('quantity');
            $new_quantity = $material - 1;
            $information = ['quantity' => $new_quantity];
            $material->update($information);
        */

        /* Eliminamos los inventos que han sido usados para crear este nuevo invento */
        if ($request->has('inventions')) {
            foreach ($selected_inventions as $type => $array_inventions) {
                $information = ['invention_created_id' => $new_invention->_id];
                Invention::whereIn('id', $array_inventions)->update($information);
                Invention::whereIn('id', $array_inventions)->delete();
                //Invention::destroy($invention_used->id);
            }
        }

        /* Codigo a revisar para convertir el array de arrays en un array simple y actualizar y eliminar los inventos en bloque */
        if ($request->has('inventions')) {
            // Recuperamos todos los ids eliminando el array de arrays ya que nos da igual el tipo
            $inventions_ids = collect($selected_inventions)->flatten()->all();

            // Actualizamos todos los inventos en bloque
            Invention::whereIn('id', $inventions_ids)->update(['invention_created_id' => $new_invention->_id]);

            // Eliminamos todos los inventos seleccionados en bloque
            Invention::whereIn('id', $inventions_ids)->delete();
        }


        /* Consultamos el id del tipo de acción crear inventos */
        $action_type_id = ActionType::where('name', 'Crear')->first()->id;

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

        return redirect()->route('inventions.show', $new_invention->_id)
                         ->with('success', "El invento $new_invention->name ha sido creado");

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
            ->with('success', "El invento $invention->name ha sido eliminado");
    }

}
