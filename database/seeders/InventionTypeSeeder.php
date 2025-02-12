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

    }
}
