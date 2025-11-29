<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Zone;

interface FreeSoundServiceInterface
{
    public function getSound(Zone $zone): ?string;
    public function getSoundUrl(string $query): ?string;
}

