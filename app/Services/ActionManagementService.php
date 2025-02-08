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

class ActionManagementService
{
    public function __construct(
        private UserManagementService $user_service,
    ) {
    }

    /**
     * Función que crea una acción recibiendo:
     *
     * @param $action_type = 'Crear', 'Construir', 'Recolectar' o 'Mover'
     * @param $actionable_id = El id del edificio, del invento o de la zona
     * @param $model = Building o Invention o Zone
     * @param $time = Tiempo que tardará en hacer la acción a partir de ahora
     */
    public function createAction(string $action_type, string $actionable_id, string $model, int $time)
    {
        $user = auth()->user();
        $action_type_id = ActionType::where('name', $action_type)->first()->id;
        $actionable_type = "App\Models\\".$model;

        $action = Action::create([
            'user_id' => $user->_id,
            'action_type_id' => $action_type_id,
            'actionable_id' => $actionable_id,
            'actionable_type' => $actionable_type,
            'time' =>  now()->addSeconds($time), //now()->addMinutes($time),
            'finished' => false,
            'notification' => false,
            'updated' => false,
        ]);

        if ($action) {
            return $action->_id;
        } else {
            return redirect()->back()->with('error', 'Problemas al realizar la acción.');
        }
    }

    /**
     * Función para calcular el id de la última acción realizada de Mover, Crear o Recolectar
     * 
     * @param $actionType = 'Mover' o 'Crear' o 'Recolectar'
     */
    public function lastActionableByType(string $actionType)
    {
        $user = auth()->user();
        $action_type_id = ActionType::where('name', $actionType)->first()->id;
        $last_actionable_id = Action::where('user_id', $user->_id)
                            ->where('action_type_id', $action_type_id)
                            ->latest()->value('actionable_id');
        return $last_actionable_id;
    }

    /**
     * Función para calcular el id de la última acción realizada de Construir
     * 
     * @param $building_id = id del edificio a mejorar
     */
    public function lastActionConstruct(string $building_id)
    {
        $user = auth()->user();
        $action_type_id = ActionType::where('name', 'Construir')->first()->id;
        $last_action_id = Action::where('user_id', $user->_id)
                            ->where('action_type_id', $action_type_id)
                            ->where('actionable_id', $building_id)
                            ->latest()->value('id');
        return $last_action_id;
    }


    /**
     * Calcula el tiempo de desplazamiento entre dos zonas
     */
    public function calculateMoveTime(string $zone_id)
    {
        $zone = $this->getZone($zone_id);
        $user_actual_zone = $this->user_service->getUserActualZone();

        return round($this->getMoveTime($user_actual_zone, $zone));
    }


    /**
     * Obtiene una zona por ID
     */
    public function getZone(string $zone_id)
    {
        $zone = Zone::findOrFail($zone_id);
        return $zone;
    }

    /**
     * Obtiene una zona con relaciones precargadas
     */
    public function getZoneWithRelations(string $zone_id)
    {
        return Zone::with(['materials', 'inventionTypes', 'events'])->findOrFail($zone_id);
    }

    /**
     * Calcula el tiempo de movimiento entre dos zonas
     */
    public function getMoveTime($actualZone, $targetZone)
    {
        $user = auth()->user();
        $distancia_x = abs($targetZone->coord_x - $actualZone->coord_x);
        $distancia_y = abs($targetZone->coord_y - $actualZone->coord_y);
        $distancia = $distancia_x + $distancia_y;
        $velocidad_user = $this->user_service->getUserStat('Velocidad');
        $tiempo_base = 50;

        if ($velocidad_user === 0) {
            $velocidad_user = 1;
        }

        $tiempo = match(true) {
            $distancia === 0 => 0,
            $distancia === 1 => $tiempo_base - $velocidad_user,
            $distancia === 2 => $tiempo_base + ($tiempo_base / $velocidad_user),
            $distancia >= 3 => (2 * $tiempo_base) + ($tiempo_base / $velocidad_user),
            default => 0,
        };

        return max(0, $tiempo);
    }

    /**
     * Función que calcula el tiempo de la creación de los inventos
     */
    public function getInventTime()
    {

    }

}

//     /**
//      * Función que calcula el tiempo de desplazamiento entre dos zonas
//      */
//     public function calculateMoveTime(string $user_id, string $zone_id):int
//     {
//         // TO DO Revisar el tema de las excepciones

//         /* Obtenemos los datos del usuario cuyo id nos pasan */
//         $user = User::find($user_id);
//         if (!$user) {
//             throw new \Exception('Usuario no encontrado.');
//         }
//         /* Obtenemos los datos de la zona a la que se quiere viajar cuyo id nos pasan */
//         $zone = Zone::find($zone_id);
//         if (!$zone) {
//             throw new \Exception('Zona a la que se quiere viajar no encontrada.');
//         }

//         /* Obtenemos el id de la zona en la que se encuentra el usuario */
//         $action_type_id = ActionType::where('name', 'Mover')->first()->id;
//         $user_action = Action::where('user_id', $user->_id)
//                                 ->where('action_type_id', $action_type_id)
//                                 ->latest('id');//->value('actionable');
// // TO DO Esta consulta devuelve null :( habrá que probar a coger el primero ordenando decreciente
//                                 //dd($user_action);
//         $user_actual_zone = Zone::find($user_action->actionable_id);

//         /* Calculamos las coordenadas de cada zona para saber distancia entre zonas */
//         $coord_x_actual_zone = $user_actual_zone->coord_x;
//         $coord_y_actual_zone = $user_actual_zone->coord_y;
//         $coord_x_zone = $zone->coord_x;
//         $coord_y_zone = $zone->coord_y;

//         $distancia_x = abs($coord_x_zone - $coord_x_actual_zone);
//         $distancia_y = abs($coord_y_zone - $coord_y_actual_zone);

//         /* Obtenemos el valor de la velocidad del usuario */
//         $velocidad_id = Stat::where('name', 'Velocidad')->first()->id;
//         $velocidad_user = UserStat::where('user_id', $user->_id)->where('stat_id', $velocidad_id)->value('value');

//         /* Calculamos el tiempo según la velocidad y la distancia */
//         $tiempo_base = 50;

//         if ($distancia_x <= 1 && $distancia_y <= 1) {
//             $tiempo = $tiempo_base - ($tiempo_base / $velocidad_user);
//         } else {
//             $tiempo = (2 * $tiempo_base) - ($tiempo_base / $velocidad_user);
//         }

//         return max(0, $tiempo);

//     }

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
