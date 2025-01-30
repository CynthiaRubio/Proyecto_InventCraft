<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Inventory;
use App\Models\InventoryMaterial;
use App\Models\Material;

class InventoryMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materials = Material::all();

        $inventories = Inventory::all();

        foreach ($inventories as $inventory) {
            foreach ($materials as $material){
                InventoryMaterial::create([
                    'inventory_id' => $inventory->_id,
                    'material_id' => $material->_id,
                    'quantity' => rand(1, 5),
                ]);
            }
        }     
    }
}
