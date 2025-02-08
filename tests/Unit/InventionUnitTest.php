<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Invention;
use App\Models\InventionType;
use App\Models\Material;
use App\Models\Inventory;
use App\Models\Building;
use App\Models\ActionBuilding;
use App\Models\Action;
use App\Models\Resource;

use Illuminate\Support\Facades\Artisan;

class InventionUnitTest extends TestCase
{
    private $invention;
    private $inventionType;
    private $material;
    private $inventory;
    private $action;
    private $actionBuilding;

    /**
     * Función que prepara el entorno de las pruebas
     */
    protected function inventionSetUp()
    {

        /* Rellenamos la base de datos */
        Artisan::call('migrate:refresh --seed');

        /* Creamos un tipo de invento */
        $this->inventionType = InventionType::create([
            'name' => 'Prueba',
            'description' => 'Tipo de invento Prueba',
        ]);

        /* Creamos un material */
        $this->material = Material::create([
            'name' => 'Material prueba',
            'description' => 'Descripción del Material prueba',
        ]);

        /* Creamos un inventario */
        $this->inventory = Inventory::create([
            'name' => 'test',
            'description' => 'Descripción del Inventario test',
        ]);

        $building = Building::all()->random();

        /* Crear una acción construir */
        $action_construir = Action::create([
            'user_id' => 1,
            'action_type_id' => 2,
            'actionable_id' => $building->_id,
            'actionable_type' => Building::class,
            'time' => now(),
            'finished' => false,
            'notification' => false,
            'updated' => false,
        ]);

        /* Crear un ActionBuilding */
        $this->actionBuilding = ActionBuilding::create([
            'action_id' => $action_construir->_id,
            'building_id' => $building->_id,
            'efficiency' => 45,
            'available' => true,
        ]);

        /* Creamos una invento */
        $this->invention = Invention::create([
            'invention_type_id' => $this->inventionType->_id,
            'material_id' => $this->material->_id,
            'inventory_id' => $this->inventory->_id,
            'action_building_id' => $this->actionBuilding->_id,
            'invention_created_id' => null,
            'name' => 'Invento 1 prueba',
            'efficiency' => 34.70,
            'available' => true,
        ]);

        /* Creamos una acción crear invento */
        $this->action = Action::create([
            'user_id' => 2,
            'action_type_id' => 1,
            'actionable_id' => $this->invention->_id,
            'actionable_type' => Invention::class,
            'time' => now(),
            'finished' => false,
            'notification' => false,
            'updated' => false,
        ]);

    }

    /**
     * Test que comprueba la relacion Invention-InventionType
     */
    public function test_invention_belongs_to_invention_type()
    {
        $this->inventionSetUp();

        /* Verificamos que el invento tiene asociado el tipo de invento */
        $this->assertTrue($this->invention->inventionType->id === $this->inventionType->id);

        /* Verificamos que el tipo de invento asociado es una instancia de InventionType */
        $this->assertInstanceOf(InventionType::class, $this->invention->inventionType);
    }

    /**
     * Test que comprueba la relación Invention-Material
     */
    public function test_invention_belongs_to_material()
    {
        $this->inventionSetUp();

        /* Verificamos que el invento tiene asociado el material */
        $this->assertTrue($this->invention->material->id === $this->material->id);

        /* Verificamos que el material asociado es una instancia de Material */
        $this->assertInstanceOf(Material::class, $this->invention->material);
    }

    /**
     * Test que comprueba la relación de Invention-Inventory
     */
    public function test_invention_belongs_to_inventory()
    {
        $this->inventionSetUp();

        /* Verificamos que el invento tiene el inventario asociado */
        $this->assertTrue($this->invention->inventory->id === $this->inventory->id);

        /* Verificamos que el inventario asociado es una instancia de Inventory */
        $this->assertInstanceOf(Inventory::class, $this->invention->inventory);
    }

    public function test_invention_belongs_to_action_building()
    {

        $this->inventionSetUp();

        /* Verificamos que el invento tiene el ActionBuilding asociado */
        $this->assertTrue($this->invention->actionBuilding->id === $this->actionBuilding->id);

        /* Verificamos que el ActionBuilding asociado es una instancia de ActionBuilding */
        $this->assertInstanceOf(ActionBuilding::class, $this->invention->actionBuilding);
    }

    public function test_invention_has_one_action()
    {
        $this->inventionSetUp();

        /* Verificamos que el invento tiene la acción de construir asociada */
        $this->assertTrue($this->invention->id === $this->action->actionable_id);

        /* Verificamos que la acción asociada es una instancia de Action */
        $this->assertInstanceOf(Action::class, $this->invention->action);
    }

    public function test_invention_has_many_resources()
    {
        $this->inventionSetUp();

        /* Creamos recursos asociados al invento */
        $resource1 = Resource::create([
            'resourceable_id' => $this->invention->id,
            'resourceable_type' => Invention::class,
            'name' => 'Recurso 1',
        ]);
        $resource2 = Resource::create([
            'resourceable_id' => $this->invention->id,
            'resourceable_type' => Invention::class,
            'name' => 'Recurso 2',
        ]);

        /* Verificamos que el invento tiene los recursos asociados */
        $this->assertTrue($this->invention->resources->count() === 2);

        /* Verificamos que los recursos son instancias de Resource */
        $this->assertInstanceOf(Resource::class, $this->invention->resources->first());
    }


    public function test_invention_has_reflexive_relationship()
    {
        $this->inventionSetUp();

        $invention2 = Invention::create([
            'invention_created_id' => $this->invention->id,
            'name' => 'Invento 2',
            'efficiency' => 87,
            'available' => true,
        ]);

        /* Verificamos la relación reflexiva de inventos */
        $this->assertTrue($invention2->inventionUsed->id === $this->invention->id);

        /* Verificar que el invento usado es una instancia de Invention */
        $this->assertInstanceOf(Invention::class, $invention2->inventionUsed);
    }

}
