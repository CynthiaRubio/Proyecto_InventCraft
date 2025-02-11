<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\MaterialType;
use App\Models\InventionType;
use App\Models\Material;
use App\Models\Invention;
use App\Models\User;
use App\Models\Inventory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invention>
 */
class InventionFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $invention_types = InventionType::all();
        $invention_type = $invention_types->random();
        $material_type_id = $invention_type->material_type_id;

        $materials = Material::where('material_type_id', $material_type_id)->get();
        $material = $materials->random();

        $users = User::all();
        $user = $users->random();

        $inventory = Inventory::where('user_id', $user->_id)->first();

        return [
            'invention_type_id' => $invention_type->_id,
            'material_id' => $material->_id,
            'inventory_id' =>  $inventory->_id,
            'action_building_id' => null,
            'invention_used_id' => null,
            'name' => $this->faker->name(), //También se puede poner faker()->name()
            'efficiency' => $material->efficiency,
            'available' => true,
        ];

    }
}
