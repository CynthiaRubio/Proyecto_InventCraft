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
            ActionTypeSeeder::class,
            ZoneSeeder::class,
            EventSeeder::class,
            UserSeeder::class,
            InventorySeeder::class,
            BuildingSeeder::class,
            StatSeeder::class,
            BuildingStatSeeder::class,
            MaterialTypeSeeder::class,
            MaterialSeeder::class,
            InventionTypeSeeder::class,
            InventionTypeInventionTypeSeeder::class,
        ]);

        //Invention::factory(100)->create();
        //ActionBuilding::factory(10)->create();
        //Action::factory(10)->create();

        $this->call([
            InventionSeeder::class,
            UserStatSeeder::class,
            //ActionSeeder::class,
            //ActionZoneSeeder::class,
            //ResourceSeeder::class,
            InventoryMaterialSeeder::class,
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
