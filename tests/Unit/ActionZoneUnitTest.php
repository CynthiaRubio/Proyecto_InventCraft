<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\ActionZone;
use App\Models\Resource;
use App\Models\Action;
use App\Models\Zone;

use Illuminate\Support\Facades\Artisan;

class ActionZoneUnitTest extends TestCase
{

    private $action;
    private $zone;
    private $actionZone;

    /**
     * Función que prepara el entorno de las pruebas
     */
    protected function actionZoneSetUp()
    {

        /* Rellenamos la base de datos */
        Artisan::call('migrate:refresh --seed');

        $zones = Zone::all();
        $this->zone = $zones->random();

        /* Creamos una accion */
        $this->action = Action::create([
            'user_id' => 1,
            'action_type_id' => 2,
            'actionable_id' => $this->zone->_id,
            'actionable_type' => Zone::class,
            'time' => now(),
            'finished' => false,
            'notification' => false,
            'updated' => false,
        ]);

        /* Creamos un ActionZone */
        $this->actionZone = ActionZone::create([
            'action_id' => $this->action->_id,
            'zone_id' => $this->zone->_id,
        ]);

    }

    /**
     * Test que comprueba que una zona tiene muchos recursos
     */
    public function test_action_zone_morph_many_resources()
    {
        $this->actionZoneSetUp();

        /* Creamos recursos asociados a ActionZone */
        $resource1 = Resource::create([
            'resourceable_id' => $this->actionZone->_id,
            'resourceable_type' => ActionZone::class,
            'name' => 'Recurso 1',
            'quantity' => 10,
            'available' => false,
        ]);

        $resource2 = Resource::create([
            'resourceable_id' => $this->actionZone->id,
            'resourceable_type' => ActionZone::class,
            'name' => 'Recurso 2',
            'quantity' => 20,
            'available' => false,
        ]);

        /* Verificamos que el ActionZone tiene los recursos asociados */
        $this->assertTrue($this->actionZone->resources->contains($resource1));
        $this->assertTrue($this->actionZone->resources->contains($resource2));
    }

    /**
     * Test que comprueba que una ActionZone pertenece a una Action
     */
    public function test_action_zone_belongs_to_action()
    {
        $this->actionZoneSetUp();

        /* Verificamos que el ActionZone tiene la acción asociada */
        $this->assertEquals($this->action->id, $this->actionZone->action->id);
    }

    /**
     * Test que comprueba la relación de ActionZone con Zone.
     */
    public function test_action_zone_belongs_to_zone()
    {
        $this->actionZoneSetUp();

        /* Verificar que el ActionZone tiene la zona asociada */
        $this->assertEquals($this->zone->id, $this->actionZone->zone->id);
    }
}
