<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Material;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materials = include database_path('data/materials.php');

        foreach ($materials as $material){
            Material::create($material);
        }

        /*
        foreach ($materials as $key => $material) {

            $materialType = MaterialType::where('name', $material['category'])->first();
            $materialTypeId = $materialType->_id;

            $zones = Zone::all();
            $zone = $zones->random();
            $zoneId = $zone->_id;

            Material::create([
                'material_type_id' => $materialTypeId,
                'zone_id' => $zoneId,
                'name' => $material['name'],
                'description' => $material['description'],
                'efficiency' => rand(1, 50),
            ]);
        }
        */
    }
}
