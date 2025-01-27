<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Stat;


class StatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Se especifican los nombres de las estadísticas del jugador
        $stats = include database_path('data/stats.php');

        //Se recorre el array de estadísticas para crear cada una
        foreach ($stats as $stat) {
            Stat::create($stat);
        }
    }
}
