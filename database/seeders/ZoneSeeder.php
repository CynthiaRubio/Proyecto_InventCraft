<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Zone;

class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Se especifican los valores de los atributos de las 9 zonas
        $zones = include database_path('data/zones.php');

        // Se recorre el array para crear cada zona
        foreach ($zones as $zone) {
            Zone::create($zone);
        }
    }
}
