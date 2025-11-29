<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Zone;

interface ZoneServiceInterface
{
    public function getZone(string $zone_id): Zone;
    public function getZoneWithRelations(string $zone_id): Zone;
    public function calculateMoveTime(string $zone_id): int;
    public function getMoveTime(Zone $actualZone, Zone $targetZone): int;
}

