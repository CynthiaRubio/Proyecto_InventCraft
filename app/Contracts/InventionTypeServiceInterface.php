<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\InventionType;
use Illuminate\Database\Eloquent\Collection;

interface InventionTypeServiceInterface
{
    public function getInventionType(string $id): ?InventionType;
    public function getInventionTypeWithRelations(string $id): ?InventionType;
    public function getInventionsNeeded(string $invention_type_id): Collection;
    public function beforeGetInventionsNeeded(string $invention_type_id): ?array;
}

