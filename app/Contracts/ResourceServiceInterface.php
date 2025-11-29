<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Action;
use App\Models\Zone;
use App\Models\ActionZone;
use App\Models\Material;
use Illuminate\Support\Collection;
use Illuminate\Http\RedirectResponse;

interface ResourceServiceInterface
{
    public function calculateFarm(string $zone_id, int $time, Action $action): ?ActionZone;
    public function calculateResources(Zone $zone, int $suerte_user, int $time, float $multiplier): array;
    public function farmMaterials(Zone $zone, int $suerte_user, int $time, float $multiplier): array;
    public function calculateMaterialProbability(int $efficiency, int $suerte_user, int $time): bool;
    public function calculateMaterialQuantity(int $efficiency): int;
    public function farmInventions(Zone $zone, int $suerte_user, int $time, float $multiplier): array;
    public function calculateNumberInvention(int $suerte_user, int $time): int;
    public function saveMaterials(array $resource): Material;
    public function updateResources(Action $action): array;
    public function recolectResourcesNoAvailables(ActionZone $action_zone): Collection;
    public function updateResourcesNoAvailables(ActionZone $action_zone): void;
    public function getUserInventionsByType(): Collection;
    public function getUserInventionsByTypeWithoutRelations(): Collection;
    public function checkInventionsToConstruct($invention_types_needed, int $num_needed, Collection $user_inventions_by_type): bool|RedirectResponse;
    public function checkInventionsToCreate($invention_types_needed, Collection $user_inventions_by_type): bool|RedirectResponse;
    public function validateSelectedInventionsForBuilding(array $inventions, Collection $invention_types_needed, int $building_level): array;
    public function validateSelectedInventionsForInvention(array $inventions, Collection $invention_types_needed): array;
    public function getUserMaterialsByType(string $material_type_id): Collection;
    public function checkMaterials(Collection $user_materials, string $name): bool|RedirectResponse;
    public function decrementMaterial(string $material_id): bool;
    public function generateEvents(string $zone_id): array;
    public function finishFarmAction(Action $action, string $userName): array;
}

