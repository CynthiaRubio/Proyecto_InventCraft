<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            ActionTypeSeeder::class,
            ZoneSeeder::class,
            EventSeeder::class,
            BuildingSeeder::class,
            StatSeeder::class,
            BuildingStatSeeder::class,
            MaterialTypeSeeder::class,
            MaterialSeeder::class,
            InventionTypeSeeder::class,
            InventionTypeInventionTypeSeeder::class,
            /*
            El UserSeeder no debería estar pero así creamos un usuario 
            para las pruebas con todo lo necesario
            */
            UserSeeder::class,
        ]);
        
    }
}
