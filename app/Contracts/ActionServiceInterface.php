<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Action;
use App\Models\ActionBuilding;
use App\Models\ActionZone;
use App\Models\ActionType;

interface ActionServiceInterface
{
    public function createAction(string $action_type, string $actionable_id, string $model, int $time): Action;
    public function getLastActionableByType(string $actionType): ?string;
    public function getLastActionConstruct(string $building_id): ?string;
    public function getActionTypeId(string $name): int;
    public function createActionBuilding(Action $action, string $building_id, float $efficiency): ActionBuilding;
    public function createActionZone(Action $action, ?int $event_id = null): ActionZone;
    public function getActionZone(Action $action): ?ActionZone;
    public function getFinishedPendingAction(int $userId): ?Action;
    public function getCurrentAction(int $userId): ?Action;
    public function calculateExperienceGained(Action $action): int;
    public function finishAction(Action $action): void;
    public function getActionTypeById(int $actionTypeId): ActionType;
}

