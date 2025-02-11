<?php

namespace App\Services;

use App\Models\Action;
use App\Models\Stat;
use App\Models\UserStat;
use App\Models\User;
use App\Models\Zone;
use App\Models\Invention;
use App\Models\Building;
use App\Models\ActionType;
use App\Models\ActionZone;
use App\Models\Material;
use App\Models\Resource;
use App\Models\InventionType;
use App\Models\ActionBuilding;

class ActionManagementService
{
    public function __construct(
        private UserManagementService $user_service,
        private ZoneManagementService $zone_service,
    ) {
    }

    /**
     * Crea una acción
     *
     * @param $action_type = 'Crear', 'Construir', 'Recolectar' o 'Mover'
     * @param $actionable_id = El id del edificio, del invento o de la zona
     * @param $model = Building o Invention o Zone
     * @param $time = Tiempo que tardará en hacer la acción a partir de ahora
     */
    public function createAction(string $action_type, string $actionable_id, string $model, int $time)
    {
        $user = $this->user_service->getUser();
        $action_type_id = $this->getActionTypeId($action_type);
        $actionable_type = "App\Models\\".$model;

        $action = Action::create([
            'user_id' => $user->_id,
            'action_type_id' => $action_type_id,
            'actionable_id' => $actionable_id,
            'actionable_type' => $actionable_type,
            /* Deberían ser minutos (now()->addMinutes($time),) pero ponemos segundos para las pruebas */
            'time' =>  now()->addSeconds($time),
            'finished' => false,
            'notification' => false,
            'updated' => false,
        ]);

        return $action;
    }

    /**
     * Recupera el actionable_id de la última acción realizada de Mover, Crear o Recolectar
     * 
     * @param $actionType = 'Mover' o 'Crear' o 'Recolectar'
     */
    public function getLastActionableByType(string $actionType)
    {
        $user = $this->user_service->getUser();

        $action_type_id = $this->getActionTypeId($actionType);

        $last_actionable_id = Action::where('user_id', $user->_id)
                            ->where('action_type_id', $action_type_id)
                            ->where('finished' , true)
                            ->latest()->value('actionable_id');
        return $last_actionable_id;
    }

    /**
     * Recupera el id de la última acción realizada de Construir
     * 
     * @param $building_id = id del edificio a mejorar
     */
    public function getLastActionConstruct(string $building_id)
    {
        $user = $this->user_service->getUser();

        $action_type_id = $this->getActionTypeId('Construir');

        $last_action_id = Action::where('user_id', $user->_id)
                            ->where('action_type_id', $action_type_id)
                            ->where('actionable_id', $building_id)
                            ->where('finished' , true)
                            ->latest()->value('id');

        return $last_action_id;
    }

    /**
     * Recupera el id de ActionType según cual sea la acción a realizar
     */
    public function getActionTypeId ($name){

        $action_type_id = ActionType::where('name', $name)->first()->id;

        return $action_type_id;
    }


    /**
     * Crea un registro ActionBuilding tras una acción de construir
     */
    public function createActionBuilding($action , $building_id , $efficiency){

        $action_building = ActionBuilding::create([
            'action_id' => $action->_id,
            'building_id' => $building_id,
            'efficiency' => $efficiency,
            'available' => false,
        ]);

        return $action_building;
    }

    /**
     * Crea un registro ActionZone tras una acción de recolectar
     */
    public function createActionZone($action){

        $action_zone = ActionZone::create([
            'action_id' => $action->_id,
            'zone_id' => $action->actionable_id,
        ]);

        return $action_zone;
    }

    /**
     * Recupera el ActionZone creado con los datos de la acción
     */
    public function getActionZone($action){
        $action_zone = ActionZone::where('action_id', $action->_id)
                        ->where('zone_id' , $action->actionable_id)
                        ->first();
        return $action_zone;
    }

}

//     /**
//      * Función para calcular los recursos obtenidos al explorar una zona
//      */
//     public function calculateFarm(string $user_id, string $zone_id, int $time):array
//     {
//         /* Obtenemos los datos del usuario cuyo id nos pasan */
//         $user = User::find($user_id);
//         if (!$user) {
//             throw new \Exception('Usuario no encontrado.');
//         }

//         /* Obtenemos el valor de la suerte del usuario */
//         $suerte_id = Stat::where('name', 'Suerte')->first()->id;
//         $suerte_user = UserStat::where('user_id', $user->_id)->where('stat_id', $suerte_id)->value('value');

//         /* Obtenemos los datos de la zona que se quiere explorar incluyendo los recursos y los eventos */
//         $zone = Zone::with(['materials','inventionTypes','events'])->where('id' , $zone_id)->get();
//         if (!$zone) {
//             throw new \Exception('Zona a la que se quiere viajar no encontrada.');
//         }

//         /* Asignamos probabilidad a los recursos de tipo material segun su eficiencia */
//         $recursos = [];

//         foreach($zone->materials as $material){
//             $probabilidad = 50 - $material->efficiency + $suerte_user;
//             if($material->efficiency <= 22){
//                 $cantidad = rand(0,8);
//             } elseif($material->efficiency > 22 && $material->efficiency <= 30){
//                 $cantidad = rand(0,4);
//             } else {
//                 $cantidad = rand(0,2);
//             }

//             if($probabilidad >= rand(0,100)){
//                 $recursos[] = [
//                     'material' => $material,
//                     'quantity' => $cantidad,
//                 ];
//             }
//         }

//         /* Asignamos probabilidad de obtener un invento */
//         $probabilidad = rand(0,100) + $suerte_user;
//         if($probabilidad >= 50){
//             $recursos[] = [
//                 'invento' => $zone->inventionType->random(),
//                 'efficiency' => rand(15,30),
//             ];
//         } elseif($probabilidad >= 80){
//             for($i = 0; $i < 2; $i++){
//                 $recursos[] = [
//                     'invento' => $zone->inventionType->random(),
//                     'efficiency' => rand(15,30),
//                 ];
//             }
//         }

//         /* Determinamos la probabilidad de que ocurra algún evento, y cual de ellos */
//         $probabilidad_evento = rand(0,100);
//         if($probabilidad_evento >= 30){
//             $evento = $zone->events->random();
//             //Creo que en eventos falta como/cuanto penaliza la recolección
//         }

//         /* SEGUN LA WIKI */

//         /* Definimos el tiempo por intervalos de 30 minutos, que es el mínimo */
//         $tiempo = $time / 30;

//         /* Obtenemos el número de objetos distintos que va a encontrar el jugador por tiempo */
//         $num_recursos = (round( ($suerte_user / 10) + (rand(3,5)) ) ) * $tiempo;

//         /* Determinamos que recursos y en que cantidad recolecta el usuario */

//         return $recursos;
//     }
// }
