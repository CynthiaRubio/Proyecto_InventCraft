<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InventionTypeInventionType;
use App\Models\InventionType;

class InventionTypeInventionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $invention_types = include database_path('data/inventionTypeInventionType.php');

            foreach ($invention_types as $invention_type) {
                InventionTypeInventionType::create($invention_type);
            }
    }
}
