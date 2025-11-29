<?php

use App\Models\Building;
use App\Models\MaterialType;
use App\Models\Zone;

$roca = MaterialType::where('name', 'Roca')->first();
$fibra = MaterialType::where('name', 'Fibra')->first();
$mineral = MaterialType::where('name', 'Mineral')->first();
$metal = MaterialType::where('name', 'Metal')->first();
$arena = MaterialType::where('name', 'Arena')->first();
$madera = MaterialType::where('name', 'Madera')->first();
$resina = MaterialType::where('name', 'Resina')->first();
$organico = MaterialType::where('name', 'Orgánico')->first();


$pradera = Zone::where('name', 'Pradera')->first();
$bosque = Zone::where('name', 'Bosque')->first();
$selva = Zone::where('name', 'Selva')->first();
$desierto = Zone::where('name', 'Desierto')->first();
$montanya = Zone::where('name', 'Montaña')->first();
$lagos = Zone::where('name', 'Lagos')->first();
$p_norte = Zone::where('name', 'Polo Norte')->first();
$glaciar = Zone::where('name', 'Glaciar de Montaña')->first();
$p_sur = Zone::where('name', 'Polo Sur')->first();

$estacion_transporte = Building::where('name', 'Estación de Transporte')->first();
$manufactura = Building::where('name', 'Taller de Manufactura')->first();
$granja = Building::where('name', 'Granja')->first();
$planta_energia = Building::where('name', 'Planta de Energía')->first();
$fundicion_metales = Building::where('name', 'Fundición de Metales')->first();
$ceramica = Building::where('name', 'Taller de Cerámica')->first();
$textil = Building::where('name', 'Fábrica de Textiles')->first();
$acueducto = Building::where('name', 'Sistemas de Acueductos')->first();

return [
    [
        'material_type_id' => $roca->id,
        'zone_id' => $montanya->id,
        'building_id' => $manufactura->id,
        'name' => 'Piedra Afilada',
        'description' => 'Una piedra afilada utilizada para cortar y fabricar herramientas.',
        'level_required' => 1,
        'creation_time' => 120,
    ],
    [
        'material_type_id' => $fibra->id,
        'zone_id' => $desierto->id,
        'building_id' => $manufactura->id,
        'name' => 'Cuerda',
        'description' => 'Una cuerda hecha de fibras utilizada para atar y fabricar herramientas.',
        'level_required' => 1,
        'creation_time' => 150
    ],
    [
        'material_type_id' => $madera->id,
        'zone_id' => $glaciar->id,
        'building_id' => $planta_energia->id,
        'name' => 'Fuego',
        'description' => 'Un fuego básico utilizado para cocinar y fabricar herramientas.',
        'level_required' => 1,
        'creation_time' => 180
    ],
    [
        'material_type_id' => $arena->id,
        'zone_id' => $desierto->id,
        'building_id' => $manufactura->id,
        'name' => 'Lanza',
        'description' => 'Una lanza hecha de una piedra afilada y cuerda, utilizada para cazar.',
        'level_required' => 1,
        'creation_time' => 240
    ],
    [
        'material_type_id' => $madera->id,
        'zone_id' => $pradera->id,
        'building_id' => $manufactura->id,
        'name' => 'Arco Flecha',
        'description' => 'Un arma de largo alcance hecha con madera, una lanza y cuerda.',
        'level_required' => 1,
        'creation_time' => 300
    ],
    [
        'material_type_id' => $fibra->id,
        'zone_id' => $p_norte->id,
        'building_id' => $manufactura->id,
        'name' => 'Cesta',
        'description' => 'Una cesta utilizada para transportar objetos.',
        'level_required' => 1,
        'creation_time' => 180
    ],
    [
        'material_type_id' => $metal->id,
        'zone_id' => $bosque->id,
        'building_id' => $estacion_transporte->id,
        'name' => 'Rueda',
        'description' => 'Una rueda utilizada para construir vehículos.',
        'level_required' => 1,
        'creation_time' => 360
    ],
    [
        'material_type_id' => $fibra->id,
        'zone_id' => $pradera->id,
        'building_id' => $granja->id,
        'name' => 'Trampa',
        'description' => 'Una trampa utilizada para cazar animales.',
        'level_required' => 1,
        'creation_time' => 300
    ],
    [
        'material_type_id' => $madera->id,
        'zone_id' => $selva->id,
        'building_id' => $manufactura->id,
        'name' => 'Hacha',
        'description' => 'Una herramienta hecha de una piedra afilada y madera, utilizada para cortar.',
        'level_required' => 1,
        'creation_time' => 200
    ],
    [
        'material_type_id' => $metal->id,
        'zone_id' => $glaciar->id,
        'building_id' => $estacion_transporte->id,
        'name' => 'Carro',
        'description' => 'Un carro utilizado para transporte.',
        'level_required' => 1,
        'creation_time' => 600
    ],
    [
        'material_type_id' => $fibra->id,
        'zone_id' => $montanya->id,
        'building_id' => $granja->id,
        'name' => 'Ganadería',
        'description' => 'Ganado utilizado para agricultura y alimentación.',
        'level_required' => 1,
        'creation_time' => 720
    ],
    [
        'material_type_id' => $arena->id,
        'zone_id' => $desierto->id,
        'building_id' => $ceramica->id,
        'name' => 'Cerámica',
        'description' => 'Cerámica utilizada para cocinar y almacenar.',
        'level_required' => 1,
        'creation_time' => 300
    ],
    [
        'material_type_id' => $organico->id,
        'zone_id' => $pradera->id,
        'building_id' => $ceramica->id,
        'name' => 'Alfarería',
        'description' => 'La fabricación avanzada de cerámica para cocinar y almacenar.',
        'level_required' => 1,
        'creation_time' => 450
    ],
    [
        'material_type_id' => $resina->id,
        'zone_id' => $bosque->id,
        'building_id' => $manufactura->id,
        'name' => 'Torno',
        'description' => 'Una herramienta giratoria para fabricar cerámica y otras piezas.',
        'level_required' => 1,
        'creation_time' => 300
    ],
    [
        'material_type_id' => $fibra->id,
        'zone_id' => $desierto->id,
        'building_id' => $granja->id,
        'name' => 'Agricultura',
        'description' => 'El proceso de cultivar la tierra y producir alimentos.',
        'level_required' => 1,
        'creation_time' => 1000
    ],
    [
        'material_type_id' => $organico->id,
        'zone_id' => $bosque->id,
        'building_id' => $granja->id,
        'name' => 'Arado',
        'description' => 'Una herramienta para arar la tierra y preparar el terreno.',
        'level_required' => 1,
        'creation_time' => 400
    ],
    [
        'material_type_id' => $fibra->id,
        'zone_id' => $glaciar->id,
        'building_id' => $textil->id,
        'name' => 'Tela',
        'description' => 'Un tejido utilizado para la confección y fabricación.',
        'level_required' => 1,
        'creation_time' => 500
    ],
    [
        'material_type_id' => $madera->id,
        'zone_id' => $p_norte->id,
        'building_id' => $estacion_transporte->id,
        'name' => 'Barco',
        'description' => 'Un barco utilizado para transporte en el agua.',
        'level_required' => 1,
        'creation_time' => 800
    ],
    [
        'material_type_id' => $arena->id,
        'zone_id' => $lagos->id,
        'building_id' => $fundicion_metales->id,
        'name' => 'Vidrio',
        'description' => 'Material fabricado utilizando fuego y cerámica.',
        'level_required' => 1,
        'creation_time' => 400
    ],
    [
        'material_type_id' => $mineral->id,
        'zone_id' => $bosque->id,
        'building_id' => $fundicion_metales->id,
        'name' => 'Metalurgia',
        'description' => 'El arte de trabajar metales utilizando cerámica y fuego.',
        'level_required' => 1,
        'creation_time' => 800
    ],
    [
        'material_type_id' => $madera->id,
        'zone_id' => $p_sur->id,
        'building_id' => $acueducto->id,
        'name' => 'Molino',
        'description' => 'Un molino utilizado para moler granos.',
        'level_required' => 1,
        'creation_time' => 1200
    ],
    [
        'material_type_id' => $metal->id,
        'zone_id' => $desierto->id,
        'building_id' => $ceramica->id,
        'name' => 'Horno',
        'description' => 'Un horno utilizado para cocinar y fabricar materiales.',
        'level_required' => 1,
        'creation_time' => 600
    ],
    [
        'material_type_id' => $madera->id,
        'zone_id' => $p_sur->id,
        'building_id' => $planta_energia->id,
        'name' => 'Canal',
        'description' => 'Un sistema de canales utilizado para transportar agua.',
        'level_required' => 1,
        'creation_time' => 1500
    ],
    [
        'material_type_id' => $metal->id,
        'zone_id' => $lagos->id,
        'building_id' => $granja->id,
        'name' => 'Riego Automatizado',
        'description' => 'Un sistema avanzado de riego automático.',
        'level_required' => 1,
        'creation_time' => 1800
    ],
    [
        'material_type_id' => $mineral->id,
        'zone_id' => $pradera->id,
        'building_id' => $acueducto->id,
        'name' => 'Acueducto',
        'description' => 'Una estructura para transportar agua a largas distancias.',
        'level_required' => 1, 
        'creation_time' => 2500
    ]
];