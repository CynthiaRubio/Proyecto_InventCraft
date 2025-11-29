<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Action;
use App\Models\Building;
use Illuminate\Database\Eloquent\Collection;

interface BuildingServiceInterface
{
    public function getBuilding(string $building_id): Building;
    public function getBuildingWithRelations(string $building_id): Building;
    public function getActualLevel(string $building_id): int;
    public function getEfficiency(string $building_id): float;
    public function getConstructTime(int $building_level): int;
    public function calculateEfficiencyBuilding(string $building_id, int $building_level, array $inventions_used): float;
    public function updateUserStats(string $building_id): void;
    public function getBuildingStats(string $building_id): array;
    public function getInventionTypesNeededForBuilding(string $building_id): Collection;
    public function constructBuilding(string $building_id, int $building_level, array $inventions_used, ?int $time = null): array;
    public function finishConstructionAction(Action $action, string $userName): array;
    public function getSpaceStation(): ?Building;
    public function canBuildSpaceStation(): array;
    public function checkVictory(): bool;
}

