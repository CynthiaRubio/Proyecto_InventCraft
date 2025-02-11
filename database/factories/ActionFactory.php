<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\User;
use App\Models\Building;
use App\Models\Zone;
use App\Models\Invention;
use App\Models\ActionType;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Action>
 */
class ActionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $users = User::all();
        $buildings = Building::all();
        $zones = Zone::all();
        $inventions = Invention::all();
        $types = ActionType::all();

        $user = $users->random();

        $action_type = $types->random();

        switch ($action_type->name) {
            case 'Mover':
                $zona = $zones->random();
                $action_type_id = $action_type->_id;
                $actionable_id = $zona->_id;
                $actionable_type = Zone::class;
                break;
            case 'Recolectar':
                $zone = $zones->random();
                $action_type_id = $action_type->_id;
                $actionable_id = $zone->_id;
                $actionable_type = Zone::class;
                break;
            case 'Construir':
                $edificio = $buildings->random();
                $action_type_id = $action_type->_id;
                $actionable_id = $edificio->_id;
                $actionable_type = Building::class;
                break;
            case 'Crear':
                $invento = $inventions->random();
                $action_type_id = $action_type->_id;
                $actionable_id = $invento->_id;
                $actionable_type = Invention::class;
                break;
            default:
                /* Hay que implementar el default  */
                break;

        }
        return [
            'user_id' => $user->_id,
            'action_type_id' => $action_type_id,
            'actionable_id' => $actionable_id,
            'actionable_type' => $actionable_type,
            'time' => now()->addSeconds(rand(60, 1440)), //Deberian ser minutos addMinutes(rand(60, 240)),
            'finished' => true,
            'notification' => false,
            'updated' => true,
        ];

    }
}
