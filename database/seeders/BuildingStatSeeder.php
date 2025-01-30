<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Building;
use App\Models\Stat;
use App\Models\BuildingStat;

class BuildingStatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /* ¿Como implementamos el resto de los edificios? */

        $estacion_transporte = Building::where('name', 'Estación de Transporte')->first();
        $manufactura = Building::where('name', 'Taller de Manufactura')->first();
        $granja = Building::where('name', 'Granja')->first();
        $planta_energia = Building::where('name', 'Planta de Energía')->first();
        $fundicion_metales = Building::where('name', 'Fundición de Metales')->first();
        $ceramica = Building::where('name', 'Taller de Cerámica')->first();
        $textil = Building::where('name', 'Fábrica de Textiles')->first();
        $acueducto = Building::where('name', 'Sistemas de Acueductos')->first();
        $estacion_espacial = Building::where('name', 'Estación Espacial')->first();

        $suerte = Stat::where('name','Suerte')->first();
        $vitalidad = Stat::where('name','Vitalidad')->first();
        $ingenio = Stat::where('name','Ingenio')->first();
        $velocidad = Stat::where('name','Velocidad')->first();

        $estadisticas = Stat::all();
        $estadistica_aleatoria = $estadisticas->random();


        $building_stat = new BuildingStat();
        $building_stat->building_id = $estacion_transporte->_id;
        $building_stat->stat_id = $velocidad->_id; 
        $building_stat->value = 1;
        $building_stat->save();

        $building_stat = new BuildingStat();
        $building_stat->building_id = $granja->_id;
        $building_stat->stat_id = $vitalidad->_id; 
        $building_stat->value = 1;
        $building_stat->save();

        $building_stat = new BuildingStat();
        $building_stat->building_id = $planta_energia->_id;
        $building_stat->stat_id = $ingenio->_id; 
        $building_stat->value = 1;
        $building_stat->save();

        $building_stat = new BuildingStat();
        $building_stat->building_id = $textil->_id;
        $building_stat->stat_id = $suerte->_id; 
        $building_stat->value = 1;
        $building_stat->save();

        $building_stat = new BuildingStat();
        $building_stat->building_id = $manufactura->_id;
        $building_stat->stat_id = $velocidad->_id; 
        $building_stat->value = 2;
        $building_stat->save();

        $building_stat = new BuildingStat();
        $building_stat->building_id = $fundicion_metales->_id;
        $building_stat->stat_id = $vitalidad->_id; 
        $building_stat->value = 2;
        $building_stat->save();

        $building_stat = new BuildingStat();
        $building_stat->building_id = $ceramica->_id;
        $building_stat->stat_id = $ingenio->_id; 
        $building_stat->value = 2;
        $building_stat->save();

        $building_stat = new BuildingStat();
        $building_stat->building_id = $acueducto->_id;
        $building_stat->stat_id = $suerte->_id; 
        $building_stat->value = 2;
        $building_stat->save();

        $estacion_espacial = new BuildingStat();
        $building_stat->building_id = $estacion_espacial->_id;
        $building_stat->stat_id = $estadistica_aleatoria->_id; 
        $building_stat->value = 5;
        $building_stat->save();
    }
}
