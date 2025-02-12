<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Inventory;
use App\Models\Material;
use App\Models\Invention;
use App\Models\InventionType;
use App\Models\Zone;
use App\Models\Action;
use App\Models\ActionType;
use App\Models\Stat;
use App\Models\UserStat;
use App\Models\InventoryMaterial;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * Reproduccimos los pasos de un registro
         */
        $user = new User();
        $user->name = 'Cynthia';
        $user->email = 'cynrusan@gmail.com';
        $user->password = bcrypt('juego_servidor');
        $user->remember_token = Str::random(10);
        $user->level = 1;
        $user->experience = 0;
        $user->unasigned_points = 15;
        $user->avatar = 1;
        $user->save();

        $inventory = Inventory::create([
                        'user_id' => $user->_id,
                    ]);

        $action_type = ActionType::where('name', 'Mover')->first();
        $zones = Zone::all();
        $zone = $zones->random();

        Action::create([
            'user_id' => $user->_id,
            'action_type_id' => $action_type->_id,
            'actionable_id' => $zone->_id,
            'actionable_type' => Zone::class,
            'time' => now(),
            'finished' => true,
            'notificacion' => true,
            'updated' => true,
        ]);

        $stats = Stat::all();
        foreach ($stats as $stat) {
            UserStat::create([
                'stat_id' => $stat->id,
                'user_id' => $user->id,
                'value' => 0,
            ]);

        }

        /**
         * Llenamos el inventario con materiales e inventos
         */

        $materials = Material::all();
        foreach ($materials as $material) {
            InventoryMaterial::create([
                'inventory_id' => $inventory->_id,
                'material_id' => $material->_id,
                'quantity' => 10,
                'quantity_na' => 0,
            ]);
        }

        $invention_types = InventionType::all();
        foreach ($invention_types as $type) {
            $materials = Material::where('material_type_id', $type->material_type_id)->get();
            for($i=0; $i<10; $i++){
                $material = $materials->random();
                Invention::create([
                    'invention_type_id' => $type->_id,
                    'material_id' => $material->_id,
                    'inventory_id' =>  $inventory->_id,
                    'action_building_id' => null,
                    'invention_used_id' => null,
                    'name' => fake()->name(),
                    'efficiency' => $material->efficiency,
                    'available' => true,
                ]);
            }
            
        }
    }
}
