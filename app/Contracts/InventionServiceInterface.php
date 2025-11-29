<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Action;
use App\Models\Invention;

interface InventionServiceInterface
{
    public function createInvention(string $invention_type_id, string $material_id, int $time): Invention;
    public function efficiencyInvention(string $material_id, int $time): float;
    public function eliminateInventionsUsed(array $inventions, string $id, string $model): void;
    public function createInventionWithAction(string $invention_type_id, string $material_id, int $time, ?array $inventions_used = null): array;
    public function finishCreationAction(Action $action, string $userName): array;
}

