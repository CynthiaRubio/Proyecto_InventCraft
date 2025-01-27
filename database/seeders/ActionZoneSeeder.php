<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Action;
use App\Models\ActionZone;
use App\Models\Zone;

class ActionZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $actions = Action::where('actionable_type', Zone::class)->get();
        $zones = Zone::all();

        foreach ($zones as $zone) {
            foreach ($actions as $action) {
                ActionZone::create([
                    'action_id' => $action->id,
                    'zone_id' => $zone->id,
                ]);
            }
        }
    }
}
