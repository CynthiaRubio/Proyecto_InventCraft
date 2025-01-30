<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Action;
use App\Models\Building;
use App\Models\Invention;
use App\Models\User;
use App\Models\Zone;
use App\Models\Resource;
use App\Models\ActionType;
use App\Models\ActionZone;

class ActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        /* TO DO Este seeder es incorrecto, da lo mismo porque va fuera */
        $users = User::all();
        $buildings = Building::all();
        $zones = Zone::all();
        $inventions = Invention::all();
        $types = ActionType::all();
        $resources = Resource::all();
        $actionZones = ActionZone::all();

        foreach($users as $user){

            $action_type = $types->random();

            switch($action_type->name){
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
            
            Action::create([
                'user_id' => $user->_id,
                'action_type_id' => $action_type_id,
                'actionable_id' => $actionable_id,
                'actionable_type' => $actionable_type,
                'time' => now()->addSeconds(rand(60, 1440)), //Deberian ser minutos addMinutes(rand(60, 240)),
                'finished' => true,
                'notificacion' => false,
            ]);

            if($action_type->name === "Recolectar"){
                ActionZone::create([
                    'action_id' => Action::latest('id')->first()->id,
                    'zone_id' => Action::latest('id')->first()->actionable_id,
                ]);
            }
        }
    }
}
