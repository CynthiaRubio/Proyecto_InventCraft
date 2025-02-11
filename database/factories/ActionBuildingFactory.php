<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Building;
use App\Models\Action;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ActionBuilding>
 */
class ActionBuildingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $buildings = Building::all();
        $building = $buildings->random();
        
        $actions = Action::where('actionable_type', Building::class)->get();
        $action = $actions->random();

        return [
            'action_id' => $action->_id,
            'building_id' => $building->_id,
            'efficiency' => rand(1, 50),
            'available' => true,
        ];
    }
}
