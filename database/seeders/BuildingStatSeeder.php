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
        $building_stat->building_id = $estacion_transporte->id;
        $building_stat->stat_id = $velocidad->id; 
        $building_stat->value = 1;
        $building_stat->save();

        $building_stat = new BuildingStat();
        $building_stat->building_id = $estacion_transporte->id;
        $building_stat->stat_id = $suerte->id; 
        $building_stat->value = 1;
        $building_stat->save();

        $building_stat = new BuildingStat();
        $building_stat->building_id = $granja->id;
        $building_stat->stat_id = $vitalidad->id; 
        $building_stat->value = 1;
        $building_stat->save();

        $building_stat = new BuildingStat();
        $building_stat->building_id = $granja->id;
        $building_stat->stat_id = $ingenio->id; 
        $building_stat->value = 1;
        $building_stat->save();

        $building_stat = new BuildingStat();
        $building_stat->building_id = $planta_energia->id;
        $building_stat->stat_id = $ingenio->id; 
        $building_stat->value = 1;
        $building_stat->save();

        $building_stat = new BuildingStat();
        $building_stat->building_id = $planta_energia->id;
        $building_stat->stat_id = $velocidad->id; 
        $building_stat->value = 1;
        $building_stat->save();

        $building_stat = new BuildingStat();
        $building_stat->building_id = $textil->id;
        $building_stat->stat_id = $suerte->id; 
        $building_stat->value = 1;
        $building_stat->save();

        $building_stat = new BuildingStat();
        $building_stat->building_id = $textil->id;
        $building_stat->stat_id = $velocidad->id; 
        $building_stat->value = 1;
        $building_stat->save();

        $building_stat = new BuildingStat();
        $building_stat->building_id = $manufactura->id;
        $building_stat->stat_id = $velocidad->id; 
        $building_stat->value = 1;
        $building_stat->save();

        $building_stat = new BuildingStat();
        $building_stat->building_id = $manufactura->id;
        $building_stat->stat_id = $ingenio->id; 
        $building_stat->value = 1;
        $building_stat->save();

        $building_stat = new BuildingStat();
        $building_stat->building_id = $fundicion_metales->id;
        $building_stat->stat_id = $vitalidad->id; 
        $building_stat->value = 1;
        $building_stat->save();

        $building_stat = new BuildingStat();
        $building_stat->building_id = $fundicion_metales->id;
        $building_stat->stat_id = $suerte->id; 
        $building_stat->value = 1;
        $building_stat->save();

        $building_stat = new BuildingStat();
        $building_stat->building_id = $ceramica->id;
        $building_stat->stat_id = $ingenio->id; 
        $building_stat->value = 1;
        $building_stat->save();

        $building_stat = new BuildingStat();
        $building_stat->building_id = $ceramica->id;
        $building_stat->stat_id = $suerte->id; 
        $building_stat->value = 1;
        $building_stat->save();

        $building_stat = new BuildingStat();
        $building_stat->building_id = $acueducto->id;
        $building_stat->stat_id = $suerte->id; 
        $building_stat->value = 1;
        $building_stat->save();

        $building_stat = new BuildingStat();
        $building_stat->building_id = $acueducto->id;
        $building_stat->stat_id = $vitalidad->id; 
        $building_stat->value = 1;
        $building_stat->save();

        $building_stat = new BuildingStat();
        $building_stat->building_id = $estacion_espacial->id;
        $building_stat->stat_id = $estadistica_aleatoria->id; 
        $building_stat->value = 0;
        $building_stat->save();
    }
}
