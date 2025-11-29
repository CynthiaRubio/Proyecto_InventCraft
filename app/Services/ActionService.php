<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ActionServiceInterface;
use App\Contracts\UserServiceInterface;
use App\Contracts\ZoneServiceInterface;
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

class ActionService implements ActionServiceInterface
{
    public function __construct(
        private UserServiceInterface $userService,
        private ZoneServiceInterface $zoneService,
    ) {
    }

    /**
     * Crea una acción para el usuario autenticado
     *
     * @param string $action_type Tipo de acción: 'Crear', 'Construir', 'Recolectar' o 'Mover'
     * @param string $actionable_id ID del edificio, invento o zona sobre el que se realiza la acción
     * @param string $model Modelo relacionado: 'Building', 'Invention' o 'Zone'
     * @param int $time Tiempo en minutos que tardará en completarse la acción
     * @return Action Acción creada
     */
    public function createAction(string $action_type, string $actionable_id, string $model, int $time): Action
    {
        $user = $this->userService->getUser();
        $action_type_id = $this->getActionTypeId($action_type);
        $actionable_type = "App\Models\\".$model;

        $action = Action::create([
            'user_id' => $user->id,
            'action_type_id' => $action_type_id,
            'actionable_id' => $actionable_id,
            'actionable_type' => $actionable_type,
            'time' => now()->addMinutes($time),
            'finished' => false,
            'notification' => false,
            'updated' => false,
        ]);

        return $action;
    }

    /**
     * Recupera el actionable_id de la última acción realizada de Mover, Crear o Recolectar
     * 
     * @param string $actionType Tipo de acción: 'Mover', 'Crear' o 'Recolectar'
     * @return string|null ID del actionable de la última acción completada o null si no existe
     */
    public function getLastActionableByType(string $actionType): ?string
    {
        $user = $this->userService->getUser();

        $action_type_id = $this->getActionTypeId($actionType);

        $last_actionable_id = Action::where('user_id', $user->id)
                            ->where('action_type_id', $action_type_id)
                            ->where('finished' , true)
                            ->latest()->value('actionable_id');
        return $last_actionable_id ? (string) $last_actionable_id : null;
    }

    /**
     * Recupera el ID de la última acción de construcción completada para un edificio
     * 
     * @param string $building_id ID del edificio
     * @return int|null ID de la última acción de construcción o null si no existe
     */
    public function getLastActionConstruct(string $building_id): ?string
    {
        $user = $this->userService->getUser();

        $action_type_id = $this->getActionTypeId('Construir');

        $last_action_id = Action::where('user_id', $user->id)
                            ->where('action_type_id', $action_type_id)
                            ->where('actionable_id', $building_id)
                            ->where('finished' , true)
                            ->latest()->value('id');

        return $last_action_id ? (string) $last_action_id : null;
    }

    /**
     * Recupera el ID de ActionType según el nombre de la acción
     * 
     * @param string $name Nombre del tipo de acción ('Mover', 'Recolectar', 'Crear', 'Construir')
     * @return int ID del tipo de acción
     */
    public function getActionTypeId(string $name): int
    {
        $action_type_id = ActionType::where('name', $name)->first()->id;

        return (int) $action_type_id;
    }


    /**
     * Crea un registro ActionBuilding tras una acción de construir
     * 
     * @param Action $action Acción de construcción
     * @param string $building_id ID del edificio construido
     * @param float $efficiency Eficiencia del edificio construido (0-100)
     * @return ActionBuilding Registro ActionBuilding creado
     */
    public function createActionBuilding(Action $action, string $building_id, float $efficiency): ActionBuilding
    {

        $action_building = ActionBuilding::create([
            'action_id' => $action->id,
            'building_id' => $building_id,
            'efficiency' => $efficiency,
            'available' => false,
        ]);

        return $action_building;
    }

    /**
     * Crea un registro ActionZone tras una acción de recolectar
     * 
     * @param Action $action Acción de recolección
     * @param int|null $event_id ID del evento que ocurrió durante la recolección (opcional)
     * @return ActionZone Registro ActionZone creado
     */
    public function createActionZone(Action $action, ?int $event_id = null): ActionZone
    {

        $action_zone = ActionZone::create([
            'action_id' => $action->id,
            'zone_id' => (string) $action->actionable_id,
            'event_id' => $event_id ? (int) $event_id : null,
        ]);

        return $action_zone;
    }

    /**
     * Recupera el ActionZone creado con los datos de la acción
     * 
     * @param Action $action Acción de recolección
     * @return ActionZone|null Registro ActionZone asociado o null si no existe
     */
    public function getActionZone(Action $action): ?ActionZone
    {

        $action_zone = ActionZone::where('action_id', $action->id)
                        ->where('zone_id' , (string) $action->actionable_id)
                        ->first();

        return $action_zone;
    }

    /**
     * Obtiene la primera acción pendiente que ya ha terminado (time <= now)
     * 
     * @param int $userId ID del usuario
     * @return Action|null Primera acción completada pendiente de procesar o null si no hay ninguna
     */
    public function getFinishedPendingAction(int $userId): ?Action
    {
        return Action::where('user_id', $userId)
            ->where('finished', false)
            ->where('time', '<=', now())
            ->first();
    }

    /**
     * Obtiene la primera acción en curso (aún no terminada, time > now)
     * 
     * @param int $userId ID del usuario
     * @return Action|null Primera acción en curso o null si no hay ninguna
     */
    public function getCurrentAction(int $userId): ?Action
    {
        return Action::where('user_id', $userId)
            ->where('finished', false)
            ->where('time', '>', now())
            ->first();
    }

    /**
     * Calcula la experiencia ganada por una acción completada
     * La fórmula es: 3 * (nivel_usuario + 1) * (tiempo_segundos / 30)
     * 
     * @param Action $action Acción completada
     * @return int Cantidad de experiencia ganada
     */
    public function calculateExperienceGained(Action $action): int
    {
        $time = $action->time->diffInSeconds($action->created_at);
        $user = $this->userService->getUser();
        return (int) round(3 * ($user->level + 1) * ($time / 30));
    }

    /**
     * Finaliza una acción y actualiza su estado
     * Marca la acción como finished=true, notification=true, updated=true
     * 
     * @param Action $action Acción a finalizar
     * @return void
     */
    public function finishAction(Action $action): void
    {
        $action->update([
            'finished' => true,
            'notification' => true,
            'updated' => true,
        ]);
    }

    /**
     * Obtiene el tipo de acción por su ID
     * 
     * @param int $actionTypeId ID del tipo de acción
     * @return ActionType|null Tipo de acción o null si no existe
     */
    public function getActionTypeById(int $actionTypeId): ActionType
    {
        return ActionType::findOrFail($actionTypeId);
    }

}
