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

class ActionManagementService
{
    protected $user_service;

    public function __construct(
        UserManagementService $userService,
    ) {
        $this->user_service = $userService;
    }

    /**
     * Calcula el tiempo de desplazamiento entre dos zonas
     */
    public function calculateMoveTime(string $user_id, string $zone_id)
    {
        $user = $this->user_service->getUserById($user_id);
        $zone = $this->getZone($zone_id);
        $user_actual_zone = $this->user_service->getUserActualZone($user);

        return round($this->getMoveTime($user, $user_actual_zone, $zone));
    }

    /**
     * Calcula los recursos obtenidos al explorar una zona
     */
    public function calculateFarm(string $user_id, string $zone_id, int $time)
    {
        $user = $this->user_service->getUserById($user_id);
        $zone = $this->getZoneWithRelations($zone_id);
        $suerte_user = $this->user_service->getUserStat($user, 'Suerte');

        $farm_result = $this->calculateResources($zone, $suerte_user, $time);

        return $farm_result;
    }

    /**
     * Obtiene una zona por ID
     */
    public function getZone(string $zone_id)
    {
        return Zone::findOrFail($zone_id);
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
    public function getMoveTime($user, $actualZone, $targetZone)
    {
        $distancia_x = abs($targetZone->coord_x - $actualZone->coord_x);
        $distancia_y = abs($targetZone->coord_y - $actualZone->coord_y);
        $distancia = $distancia_x + $distancia_y;
        $velocidad_user = $this->user_service->getUserStat($user, 'Velocidad');
        $tiempo_base = 50;

        if($velocidad_user === 0){
            $velocidad_user = 1;
        }

        $tiempo = match(true){
            $distancia === 0 => 0,
            $distancia === 1 => $tiempo_base - $velocidad_user,
            $distancia === 2 => $tiempo_base + ($tiempo_base / $velocidad_user),
            $distancia >= 3 => (2 * $tiempo_base) + ($tiempo_base / $velocidad_user),
            default => 0,
        };

        return max(0, $tiempo);
    }

    /**
     * Calcula los recursos disponibles en una zona según la suerte del usuario
     */
    public function calculateResources($zone, int $suerte_user, int $time) //TENER EN CUENTA LO DEL TIEMPO
    {
        $recursos = [];

        foreach ($zone->materials as $material) {

            if ($this->calculateResourceProbability($material->efficiency, $suerte_user)) {
                $recursos[] = [
                    'material' => $material,
                    'quantity' => $this->calculateResourceQuantity($material->efficiency)
                ];
            }
        }

        $recursos = array_merge($recursos, $this->calculateInventions($zone, $suerte_user));
        
        return $recursos;
    }

    /**
     * Calcula la probabilidad de obtener un recurso
     */
    public function calculateResourceProbability(int $efficiency, int $luck)
    {
        $probabilidad = 50 - $efficiency + $luck;
        if($probabilidad >= rand(0,100)){
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determina la cantidad de un recurso basado en su eficiencia
     */
    public function calculateResourceQuantity(int $efficiency)
    {
        return match (true) {
            $efficiency <= 22 => rand(0, 8),
            $efficiency > 22 && $efficiency <= 30 => rand(0, 4),
            default => rand(0, 2)
        };
    }

    /**
     * Calcula la probabilidad de encontrar un invento en la zona
     */
    public function calculateInventions($zone, int $luck)
    {
        $probabilidad = rand(0, 100) + $luck;
        $inventions = [];

        if ($probabilidad >= 50) {
            $inventions[] = ['invento' => $zone->inventionTypes->random(), 'efficiency' => rand(15, 30)];
        }

        if ($probabilidad >= 80) {
            for ($i = 0; $i < 2; $i++) {
                $inventions[] = ['invento' => $zone->inventionTypes->random(), 'efficiency' => rand(15, 30)];
            }
        }

        return $inventions;
    }

    /**
     * Función que crea una acción
     */
    public function createAction(string $action_type, string $actionable_id, string $model, int $time )
    {
        $user = auth()->user();
        $action_type_id = ActionType::where('name', $action_type)->first()->id;
        $actionable_type = "App\Models\\" .$model;

        $action = Action::create([
            'user_id' => $user->_id,
            'action_type_id' => $action_type_id,
            'actionable_id' => $actionable_id,
            'actionable_type' => $actionable_type,
            'time' =>  now()->addSeconds($time), //now()->addMinutes(rand(60, 240)),
            'finished' => false,
            'notificacion' => false,
        ]);

        if($action){
            return true;
        } else {
            return false;
        }
    }

    /**
     * Función que comprueba que no hay acciones activas
     */
    public function activeAction (){

        $user_id = auth()->user()->id;
        $action = Action::where('user_id', $user_id);
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
