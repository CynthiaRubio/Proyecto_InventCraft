<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Action;
use App\Models\User;
use App\Models\ActionType;
use App\Models\Zone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Artisan;

class ActionUnitTest extends TestCase
{
    use WithoutMiddleware;

    private $action;
    private $user;
    private $actionType;
    private $model;

    /**
     * Función que prepara el entorno de las pruebas
     */
    protected function actionSetUp(){

        /* Rellenamos la base de datos */
        Artisan::call('migrate:refresh --seed');

        /* Creamos un usuario de prueba */
        $this->user = User::factory()->create();

        /* Recogemos un tipo de acción aleatoria */
        $this->actionType = ActionType::all()->random();

        /* Definimos el modelo según el tipo de acción */
        $this->model = match($this->actionType->name){
            'Mover' => 'Zone',
            'Recolectar' => 'Zone',
            'Crear' => 'Invention',
            'Construir' => 'Building',
        };

        $this->action = Action::create([
            'user_id' => $this->user->id,
            'action_type_id' => $this->actionType->_id,
            'actionable_id' => 1,
            'actionable_type' => 'App\Models\\'.$this->model,
            'time' => now(),
            'finished' => false,
            'notification' => false,
            'updated' => false,
        ]);
    }


    /**
     * Test que verifica si la acción ha sido creada
     */
    public function test_create_action()
    {
        $this->actionSetUp();
        $this->assertDatabaseHas('actions', [
            'user_id' => $this->user->id,
            'action_type_id' => $this->actionType->id,
            'actionable_id' => 1,
            'actionable_type' => 'App\Models\\'.$this->model,
        ]);
    }

    /**
     * Test que comprueba si la relación polimórfica se ha cargado
     */
    public function test_action_has_actionable()
    {
        Artisan::call('migrate --seed');

        $actionType = ActionType::where('name','Mover')->first();

        $zone = Zone::first();

        $action = Action::create([
            'user_id' => 1,
            'action_type_id' => $actionType->id,
            'actionable_id' => $zone->id,
            'actionable_type' => 'App\Models\Zone',
            'time' => now(),
            'finished' => false,
            'notification' => false,
            'updated' => false,
        ]);

        $this->assertInstanceOf(Zone::class, $action->actionable);
    }


    /**
     * Test que comprueba la relación con User
     */
    public function test_action_belongs_to_user()
    {
        $this->actionSetUp();
        $this->assertEquals($this->user->id, $this->action->user->id);
    }


    /**
     * Test que comprueba la relación con ActionType
     */
    public function test_action_belongs_to_action_type()
    {
        $this->actionSetUp();
        $this->assertEquals($this->actionType->id, $this->action->actionType->id);
    }


    /**
     * Test que comprueba la actualización de la acción
     */
    public function test_update_action()
    {
        $this->actionSetUp();

        $this->action->update([
            'finished' => true,
            'notification' => true,
        ]);

        $this->assertDatabaseHas('actions', [
            'finished' => true,
            'notification' => true,
        ]);
    }


    /**
     * Test que comprueba que la acción se ha eliminado de la base de datos
     */
    public function test_delete_action()
    {
        $this->actionSetUp();

        $this->action->delete();

        $this->assertDatabaseMissing('actions', [
            'user_id' => $this->user->id,
            'action_type_id' => $this->actionType->id,
            'actionable_id' => 1,
            'actionable_type' => 'App\Models\\'.$this->model,
        ]);
    }



























    // public function test_create_action()
    // {
    //     Artisan::call('migrate --seed');

    //     // Crear un usuario de prueba
    //     $user = User::factory()->create();

    //     // Crear un ActionType de prueba
    //     $actionType = ActionType::first();

    //     // Crear una Action
    //     $action = Action::create([
    //         'user_id' => $user->id,
    //         'action_type_id' => $actionType->_id,
    //         'actionable_id' => 1,  // Asumiendo que se usará algún valor para el actional
    //         'actionable_type' => 'Zone',  // Suponiendo que es una zona, ajusta según sea necesario
    //         'time' => now(),
    //         'finished' => false,
    //         'notification' => false,
    //         'updated' => false,
    //     ]);

    //     // Verificar que la acción ha sido creada en la base de datos
    //     $this->assertDatabaseHas('actions', [
    //         'user_id' => $user->id,
    //         'action_type_id' => $actionType->id,
    //         'actionable_id' => 1,
    //         'actionable_type' => 'Zone',
    //     ]);
    // }

    // public function test_action_has_actionable()
    // {
    //     Artisan::call('migrate --seed');

    //     // Crear un ActionType de prueba
    //     $actionType = ActionType::first();

    //     // Crear un objeto 'Zone' (o cualquier otro que se pueda asociar)
    //     $zone = Zone::first();

    //     // Crear una Action asociada a Zone
    //     $action = Action::create([
    //         'user_id' => 1,
    //         'action_type_id' => $actionType->id,
    //         'actionable_id' => $zone->id,
    //         'actionable_type' => 'App\Models\Zone',
    //         'time' => now(),
    //         'finished' => false,
    //         'notification' => false,
    //         'updated' => false,
    //     ]);

    //     // Comprobar que la relación polimórfica se cargue correctamente
    //     $this->assertInstanceOf(Zone::class, $action->actionable);
    // }

    // public function test_action_belongs_to_user()
    // {
    //     Artisan::call('migrate --seed');

    //     // Crear un usuario de prueba
    //     $user = User::factory()->create();

    //     // Crear un Action de prueba asociado a ese usuario
    //     $action = Action::create([
    //         'user_id' => $user->id,
    //         'action_type_id' => 1,  // Suponiendo que ActionType con id=1 existe
    //         'actionable_id' => 1,  // Id de la entidad actionable
    //         'actionable_type' => 'Zone',
    //         'time' => now(),
    //         'finished' => false,
    //         'notification' => false,
    //         'updated' => false,
    //     ]);

    //     // Verificar la relación con User
    //     $this->assertEquals($user->id, $action->user->id);
    // }


    // public function test_action_belongs_to_action_type()
    // {
    //     Artisan::call('migrate --seed');

    //     // Crear un ActionType de prueba
    //     $actionType = ActionType::first();

    //     // Crear un Action de prueba asociado a ese ActionType
    //     $action = Action::create([
    //         'user_id' => 1,
    //         'action_type_id' => $actionType->id,
    //         'actionable_id' => 1,
    //         'actionable_type' => 'Zone',
    //         'time' => now(),
    //         'finished' => false,
    //         'notification' => 'Some notification',
    //         'updated' => now(),
    //     ]);

    //     // Verificar la relación con ActionType
    //     $this->assertEquals($actionType->id, $action->actionType->id);
    // }


    // public function test_update_action()
    // {
    //     Artisan::call('migrate --seed');

    //     // Crear un ActionType de prueba
    //     $actionType = ActionType::first();

    //     // Crear una Action de prueba
    //     $action = Action::create([
    //         'user_id' => 1,
    //         'action_type_id' => $actionType->id,
    //         'actionable_id' => 1,
    //         'actionable_type' => 'Zone',
    //         'time' => now(),
    //         'finished' => false,
    //         'notification' => 'Some notification',
    //         'updated' => now(),
    //     ]);

    //     // Actualizar la acción
    //     $action->update([
    //         'finished' => true,
    //         'notification' => 'Updated notification',
    //     ]);

    //     // Verificar que los datos fueron actualizados correctamente
    //     $this->assertDatabaseHas('actions', [
    //         'finished' => true,
    //         'notification' => 'Updated notification',
    //     ]);
    // }

    // public function test_delete_action()
    // {
    //     Artisan::call('migrate --seed');

    //     // Crear una Action de prueba
    //     $action = Action::create([
    //         'user_id' => 1,
    //         'action_type_id' => 1,
    //         'actionable_id' => 1,
    //         'actionable_type' => 'Zone',
    //         'time' => now(),
    //         'finished' => false,
    //         'notification' => 'Some notification',
    //         'updated' => now(),
    //     ]);

    //     // Eliminar la acción
    //     $action->delete();

    //     // Verificar que la acción ha sido eliminada
    //     $this->assertDatabaseMissing('actions', [
    //         'user_id' => 1,
    //         'action_type_id' => 1,
    //         'actionable_id' => 1,
    //         'actionable_type' => 'Zone',
    //     ]);
    // }


}
