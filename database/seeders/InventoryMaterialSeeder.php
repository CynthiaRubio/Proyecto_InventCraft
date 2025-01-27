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

        /* TO DO Revisar el código de Veselin */

        $materials = Material::all();

        /* No vale coger todos los inventarios, habría que coger el inventario del usuario */
        $inventories = Inventory::all();

        foreach ($inventories as $inventory) {
            for ($i=0; $i < 5 ; $i++) {
                $material = $materials->random();
                $resultado = InventoryMaterial::where('inventory_id' , $inventory->_id)->where('material_id' , $material->_id)->count();
                if($resultado > 0){
                    $material = $materials->random();
                }
                InventoryMaterial::create([
                    'inventory_id' => $inventory->_id,
                    'material_id' => $material->_id,
                    'quantity' => rand(1, 5),
                ]);
            }
        }     
    }
}
