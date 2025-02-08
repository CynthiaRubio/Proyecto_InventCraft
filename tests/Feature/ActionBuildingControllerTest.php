<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Models\Action;
use App\Models\Building;
use App\Models\ActionBuilding;
use App\Models\User;
use App\Models\Inventory;
use App\Models\InventionType;
use App\Models\Invention;
use App\Models\Material;
use App\Http\Controllers\ActionBuildingController;

class ActionBuildingControllerTest extends TestCase
{
    use WithoutMiddleware;

    public function test_create_action_building_form()
    {

        /* Rellenamos la base de datos */
        Artisan::call('migrate:refresh --seed');

        /* Creamos un usuario */
        $user = User::all()->random();

        /* Cogemos un edificio al azar */
        $building = Building::all()->random();

        /* Recuperamos el inventario del usuario */
        $inventory = Inventory::where('user_id', $user->id)->first();

        /* Recuperamos los tipos de inventos relacionados con el edificio */
        $inventionType = InventionType::where('building_id', $building->id)->get();

        //Invention::factory(100)->create();
        
        /* Creamos inventos en el inventario del usuario */
        // $inventory->inventions()->create([
        //     'invention_type_id' => $inventionType->id,
        //     'material_id' => Material::all()->random(),
        //     'inventory_id' => $inventory->id,
        //     'action_building_id' => null,
        //     'invention_created_id' => null,
        //     'name' => 'prueba',
        //     'efficiency' => 10,
        //     'available' => true,
        // ]);

        // Llamar al endpoint para crear un edificio
        $response = $this->get(route('actionBuildings.create.withBuilding', $building->_id));

        // Verificar que se muestra el formulario
        
        $response->assertSuccessful();
        //$response->assertViewHas(['building'=> $building]);
    }

    public function test_validate_inventions_for_building()
    {
        /* Rellenamos la base de datos */
        Artisan::call('migrate:refresh --seed');

        /* Creamos un usuario */
        $user = User::all()->random();

        /* Cogemos un edificio al azar */
        $building = Building::all()->random();

        // Crear un inventario para el usuario
        $inventory = Inventory::factory()->create(['user_id' => $user->id]);
        $inventionType = InventionType::factory()->create(['building_id' => $building->id]);
        $inventory->inventions()->create(['invention_type_id' => $inventionType->id, 'efficiency' => 10]);

        // Llamar al método create con datos incorrectos (insuficientes inventos)
        $response = $this->post(route('actionBuildings.store'), [
            'building_id' => $building->id,
            'building_level' => 2,
            'inventions' => [],
        ]);

        // Verificar que se muestra el error
        $response->assertRedirect(route('buildings.index'));
        $response->assertSessionHas('error', 'No tienes suficientes inventos de tipo '.$inventionType->name);
    }

    public function test_store_action_building()
    {
        // Crear un usuario y autenticarlo
        $user = User::factory()->create();
        $this->actingAs($user);

        // Crear un edificio de prueba
        $building = Building::factory()->create();

        // Crear un inventario para el usuario
        $inventory = Inventory::factory()->create(['user_id' => $user->id]);
        $inventionType = InventionType::factory()->create(['building_id' => $building->id]);
        $inventory->inventions()->create(['invention_type_id' => $inventionType->id, 'efficiency' => 10]);

        // Llamar al método store para crear la acción de construcción
        $response = $this->post(route('action_building.store'), [
            'building_id' => $building->id,
            'building_level' => 1,
            'inventions' => [
                $inventionType->id => [$inventory->inventions->first()->id],
            ],
        ]);

        // Verificar que se ha redirigido correctamente
        $response->assertRedirect(route('buildings.show', $building->id));
        $response->assertSessionHas('success', "$user->name has creado este edificio satisfactoriamente.");

        // Verificar que se ha creado la acción de construcción en la base de datos
        $this->assertDatabaseHas('actions', [
            'user_id' => $user->id,
            'action_type_id' => 1, // Suponiendo que 'Construir' tiene el ID 1
            'actionable_id' => $building->id,
            'actionable_type' => Building::class,
        ]);

        // Verificar que se ha creado el ActionBuilding con la eficiencia correcta
        $this->assertDatabaseHas('action_buildings', [
            'building_id' => $building->id,
            'efficiency' => 5, // Supón que la eficiencia calculada sea 5
        ]);
    }


    public function test_validate_empty_inventions()
    {
        // Crear un usuario y autenticarlo
        $user = User::factory()->create();
        $this->actingAs($user);

        // Crear un edificio de prueba
        $building = Building::factory()->create();

        // Crear un inventario para el usuario
        $inventory = Inventory::factory()->create(['user_id' => $user->id]);
        $inventionType = InventionType::factory()->create(['building_id' => $building->id]);
        $inventory->inventions()->create(['invention_type_id' => $inventionType->id, 'efficiency' => 10]);

        // Llamar al método store sin inventos seleccionados
        $response = $this->post(route('action_building.store'), [
            'building_id' => $building->id,
            'building_level' => 1,
            'inventions' => [
                $inventionType->id => [],
            ],
        ]);

        // Verificar que se muestra el error de validación
        $response->assertSessionHasErrors('inventions.'.$inventionType->id);
        $response->assertSessionHas('error', 'Debes seleccionar al menos un invento para cada tipo.');
    }




}
