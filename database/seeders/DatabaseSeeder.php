<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Invention;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        $this->call([
            //Aqui hay que poner todos los seeders que queremos que utilice laravel
            InventorySeeder::class,
            ActionTypeSeeder::class,
            ZoneSeeder::class,
            EventSeeder::class,
            BuildingSeeder::class,
            StatSeeder::class,
            BuildingStatSeeder::class,
            UserStatSeeder::class,
            MaterialTypeSeeder::class,
            MaterialSeeder::class,
            InventionTypeSeeder::class,
        ]);

        //Invention::factory(100)->create();

        $this->call([
            InventionSeeder::class, //Esto se usaría si creasemos la llamada a factory en el seeder
            ResourceSeeder::class,
            ActionSeeder::class,
            ActionZoneSeeder::class,
            ActionBuildingSeeder::class,
            InventoryMaterialSeeder::class, // Falla porque se escogen de forma aleatoria los materiales para el inventario
            InventionTypeInventionTypeSeeder::class,
        ]);

        //Si queremos crear usuarios con valores específicos en los campos
        /*
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        */
    }
}
