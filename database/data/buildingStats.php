<?php

use App\Models\Building;
use App\Models\Stat;

$estacion_transporte = Building::where('name', 'Estación de Transporte')->first();
$manufactura = Building::where('name', 'Taller de Manufactura')->first();
$granja = Building::where('name', 'Granja')->first();
$planta_energia = Building::where('name', 'Planta de Energía')->first();
$fundicion_metales = Building::where('name', 'Fundición de Metales')->first();
$ceramica = Building::where('name', 'Taller de Cerámica')->first();
$textil = Building::where('name', 'Fábrica de Textiles')->first();
$acueducto = Building::where('name', 'Sistemas de Acueductos')->first();

$suerte = Stat::where('stat_type', 'Suerte')->get();
$vitalidad = Stat::where('stat_type', 'Vitalidad')->get();
$ingenio = Stat::where('stat_type', 'Ingenio')->get();
$velocidad = Stat::where('stat_type', 'Velocidad')->get();


return [

];
