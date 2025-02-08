<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\ActionBuilding;
use App\Models\Invention;
use App\Models\Building;
use App\Models\ActionType;
use App\Models\Action;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Artisan;


class ActionBuildingUnitTest extends TestCase
{
    use WithoutMiddleware;

    private $building;
    private $actionBuilding;

    /**
     * Función que prepara el entorno de las pruebas
     */
    protected function buildingSetUp(){

        /* Rellenamos la base de datos */
        Artisan::call('migrate:refresh --seed');

        /* Seleccionamos un edificio de forma aleatoria */
        $buildings = Building::all();
        $this->building = $buildings->random();

        /* Creamos un ActionBuilding */
        $this->actionBuilding = ActionBuilding::create([
            'action_id' => rand(0,100),
            'building_id' => $this->building->id,
            'efficiency' => 50,
            'available' => true,
            ]);
    }


    /**
     * Test para comprobar la relacion de ActionBuilding con Invento
     */
    public function test_action_building_has_inventions()
    {
        $this->buildingSetUp();

        /* Creamos inventos asociándolos a la acción de construir */
        $invention1 = Invention::create([
            'action_building_id' => $this->actionBuilding->id, 
            'name' => 'Invention 1'
        ]);
        $invention2 = Invention::create([
            'action_building_id' => $this->actionBuilding->id, 
            'name' => 'Invention 2'
        ]);

        /* Verificamos que el ActionBuilding tiene los inventos creados asociados */
        $this->assertTrue($this->actionBuilding->inventions->contains($invention1));
        $this->assertTrue($this->actionBuilding->inventions->contains($invention2));
    }

    /**
     * Test para comprobar la relación de ActionBuilding-Action
     */
    public function test_action_building_has_action()
    {
        /* Rellenamos la base de datos */
        Artisan::call('migrate:refresh --seed');

        /* Seleccionamos un edificio de forma aleatoria */
        $buildings = Building::all();
        $building = $buildings->random();

        /* Recuperamos la información cuando la acción es de tipo Construir */
        $actionType = ActionType::where('name' , 'Construir')->first();
        
        /* Creamos una nueva acción que será asociada a ActionBuilding y Building */
        $new_action = Action::create([
            'user_id' => 1,
            'action_type_id' => $actionType->id,
            'actionable_id' => $building->id,
            'actionable_type' => Building::class,
            'time' => now(),
            'finished' => false,
            'notification' => false,
            'updated' => false,
        ]);

        /* Creamos un ActionBuilding asociado a Action y Building */
        $new_actionBuilding = ActionBuilding::create([
            'action_id' => $new_action->id,
            'building_id' => $building->id,
            'efficiency' => 50,
            'available' => true,
        ]);

        // Verificar que el ActionBuilding tiene la acción asociada
        $this->assertEquals(strval($new_action->_id), strval($new_actionBuilding->action->id));
    }


    /**
     * Test que comprueba la relación de ActionBuilding-Building
     */
    public function test_action_building_has_building()
    {
        $this->buildingSetUp();

        /* Creamos un ActionBuilding asociado a Action y Building */
        $actionBuilding = ActionBuilding::create([
            'action_id' => 1,
            'building_id' => $this->building->id,
            'efficiency' => 30,
            'available' => true,
        ]);

        // Verificar que el ActionBuilding tiene el edificio asociado
        $this->assertEquals($this->building->id, $actionBuilding->building->id);
    }
}
