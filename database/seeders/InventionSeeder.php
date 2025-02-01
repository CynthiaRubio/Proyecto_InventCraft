<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Invention;
use App\Models\InventionType;
use App\Models\Material;
use App\Models\User;
use App\Models\Inventory;
use Faker\Factory as Faker;

class InventionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $invention_types = InventionType::all();
        $inventories = Inventory::all();

        foreach($invention_types as $type){

            foreach($inventories as $inventory){

                for($i = 0; $i < 2; $i++){
                    $materials = Material::where('material_type_id', $type->material_type_id)->get();
                    $material = $materials->random();
        
                    Invention::create ([
                        'invention_type_id' => $type->_id,
                        'material_id' => $material->_id,
                        'inventory_id' =>  $inventory->_id,
                        'action_building_id' => null,
                        'invention_created_id' => null,
                        'name' => $faker->name(),
                        'efficiency' => $material->efficiency,
                        'available' => true,
                    ]);
                }       
            }
        }
    }
}
