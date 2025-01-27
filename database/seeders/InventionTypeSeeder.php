<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InventionType;

class InventionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = include database_path('data/inventionType.php');

            foreach ($types as $type) {
                InventionType::create($type);
            }



        /* TO DO Revisar el código de Veselin

        $path = database_path('data/inventions.php');
        $inventions = include($path);

        foreach ($inventions as $invention) {
            InventionType::create([
                'material_type_id' => $invention['material_type'],
                'building_id' => $invention['building'],
                'name' => $invention['name'],
                'description' => $invention['description'],
                'required_inventions' => $invention['required_inventions'],
                'creation_time' => $invention['creation_time']
            ]);
        }

        $inventionsCreated = InventionType::all();

        foreach ($inventionsCreated as $inventionCreated) {
            $requiredInventions = [];
            foreach ($inventions as $invention) {
                if ($invention['name'] === $inventionCreated->name) {
                    foreach ($invention['required_inventions'] as $required) {
                        $requiredInvention = InventionType::where('name', $required['name'])->first();
                        if ($requiredInvention) {
                            $requiredInventions[] = [
                                'quantity' => $required['quantity'],
                                'id' => $requiredInvention->id,
                            ];
                        }
                    }
                    $inventionCreated->required_inventions = $requiredInventions;
                    $inventionCreated->save();
                }
            }
        }
        */

    }
}
