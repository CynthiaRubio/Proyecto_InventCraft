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
$estacion_espacial = Building::where('name', 'Estación Espacial')->first();

$suerte = Stat::where('name', 'Suerte')->first();
$vitalidad = Stat::where('name', 'Vitalidad')->first();
$ingenio = Stat::where('name', 'Ingenio')->first();
$velocidad = Stat::where('name', 'Velocidad')->first();

return [
    // Estación de Transporte: Velocidad y Suerte en 1 punto
    [
        'building_id' => $estacion_transporte->id,
        'stat_id' => $velocidad->id,
        'value' => 1,
    ],
    [
        'building_id' => $estacion_transporte->id,
        'stat_id' => $suerte->id,
        'value' => 1,
    ],

    // Taller de Manufactura: Velocidad e Ingenio en 1 punto
    [
        'building_id' => $manufactura->id,
        'stat_id' => $velocidad->id,
        'value' => 1,
    ],
    [
        'building_id' => $manufactura->id,
        'stat_id' => $ingenio->id,
        'value' => 1,
    ],

    // Granja: Vitalidad e Ingenio en 1 punto
    [
        'building_id' => $granja->id,
        'stat_id' => $vitalidad->id,
        'value' => 1,
    ],
    [
        'building_id' => $granja->id,
        'stat_id' => $ingenio->id,
        'value' => 1,
    ],

    // Planta de Energía: Ingenio y Velocidad en 1 punto
    [
        'building_id' => $planta_energia->id,
        'stat_id' => $ingenio->id,
        'value' => 1,
    ],
    [
        'building_id' => $planta_energia->id,
        'stat_id' => $velocidad->id,
        'value' => 1,
    ],

    // Fundición de Metales: Vitalidad y Suerte en 1 punto
    [
        'building_id' => $fundicion_metales->id,
        'stat_id' => $vitalidad->id,
        'value' => 1,
    ],
    [
        'building_id' => $fundicion_metales->id,
        'stat_id' => $suerte->id,
        'value' => 1,
    ],

    // Taller de Cerámica: Ingenio y Suerte en 1 punto
    [
        'building_id' => $ceramica->id,
        'stat_id' => $ingenio->id,
        'value' => 1,
    ],
    [
        'building_id' => $ceramica->id,
        'stat_id' => $suerte->id,
        'value' => 1,
    ],

    // Fábrica de Textiles: Suerte y Velocidad en 1 punto
    [
        'building_id' => $textil->id,
        'stat_id' => $suerte->id,
        'value' => 1,
    ],
    [
        'building_id' => $textil->id,
        'stat_id' => $velocidad->id,
        'value' => 1,
    ],

    // Sistema de Acueductos: Suerte y Vitalidad en 1 punto
    [
        'building_id' => $acueducto->id,
        'stat_id' => $suerte->id,
        'value' => 1,
    ],
    [
        'building_id' => $acueducto->id,
        'stat_id' => $vitalidad->id,
        'value' => 1,
    ],
    [
        'building_id' => $estacion_espacial->id,
        'stat_id' => $suerte->id, // Placeholder, se ignora en el código
        'value' => 0,
    ],
];
