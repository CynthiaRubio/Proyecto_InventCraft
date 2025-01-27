<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ActionZone;
use App\Models\Invention;
use App\Models\Material;
use App\Models\Resource;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /* TO DO Revisar */

        $actionZones = ActionZone::all();
        $materials = Material::all();
        $inventions = Invention::all();

        foreach ($actionZones as $actionZone) {
            $material = $materials->random();
            Resource::create([
                'action_zone_id' => $actionZone->_id,
                'resourceable_id' => $material->_id,
                'resourceable_type' => Material::class,
                'quantity' => rand(0, 6),
            ]);

            $invention = $inventions->random();
            Resource::create([
                'action_zone_id' => $actionZone->_id,
                'resourceable_id' => $invention->_id,
                'resourceable_type' => Invention::class,
                'quantity' => rand(0, 2),
            ]);
        }
    }
}
