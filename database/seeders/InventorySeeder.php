<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Inventory;
use App\Models\User;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        /* Se recorren los usuarios para asignar a cada inventario el id de su usuario */
        foreach ($users as $key => $user) {
            Inventory::create([
                'user_id' => $user->_id,
            ]);
        }
    }
}
