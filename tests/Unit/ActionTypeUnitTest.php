<?php

namespace Tests\Unit;

use Tests\TestCase;

use App\Models\ActionType;
use App\Models\Action;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Artisan;

class ActionTypeUnitTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * Test que verifica la creación de un ActionType.
     */
    public function test_create_action_type()
    {
        $actionType = ActionType::create([
            'name' => 'Crear',
            'description' => 'Acción para crear inventos'
        ]);

        $this->assertDatabaseHas('action_types', [
            'name' => 'Crear',
            'description' => 'Acción para crear inventos'
        ]);
    }

    /**
     * Test que verifica la relación entre ActionType y Action.
     */
    public function test_action_type_has_many_actions()
    {
        $actionType = ActionType::create([
            'name' => 'Construir',
            'description' => 'Acción para construir edificios'
        ]);

        $action1 = Action::create([
            'user_id' => 1,
            'action_type_id' => $actionType->id,
            'actionable_id' => 1,
            'actionable_type' => 'Building',
            'time' => now(),
            'finished' => false,
            'notification' => false,
            'updated' => false
        ]);

        $action2 = Action::create([
            'user_id' => 2,
            'action_type_id' => $actionType->id,
            'actionable_id' => 2,
            'actionable_type' => 'Building',
            'time' => now(),
            'finished' => false,
            'notification' => false,
            'updated' => false
        ]);

        $this->assertCount(2, $actionType->actions);
        $this->assertTrue($actionType->actions->contains($action1));
        $this->assertTrue($actionType->actions->contains($action2));
    }

    /**
     * Test que verifica si las acciones se cargan correctamente con la relación.
     */
    public function test_load_actions_relationship()
    {
        $actionType = ActionType::create([
            'name' => 'Mover',
            'description' => 'Acción de desplazarse de una zona a otra'
        ]);

        $action = Action::create([
            'user_id' => 1,
            'action_type_id' => $actionType->id,
            'actionable_id' => 1,
            'actionable_type' => 'Zone',
            'time' => now(),
            'finished' => false,
            'notification' => false,
            'updated' => false
        ]);

        $actionType->load('actions');

        $this->assertCount(1, $actionType->actions);
        $this->assertEquals($action->id, $actionType->actions->first()->id);
    }
}
