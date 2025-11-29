<?php

use App\Models\MaterialType;
use App\Models\Zone;

$roca = MaterialType::where('name', 'Roca')->first();
$mineral = MaterialType::where('name', 'Mineral')->first();
$arena = MaterialType::where('name', 'Arena')->first();
$metal = MaterialType::where('name', 'Metal')->first();
$madera = MaterialType::where('name', 'Madera')->first();
$fibra = MaterialType::where('name', 'Fibra')->first();
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



return [
    [
        "material_type_id" => $roca->id,
        "zone_id" => $pradera->id,
        "name" => "Sílex",
        "description" => "Roca sedimentaria formada por sílice microcristalina",
        "efficiency" => 12
    ],
    [
        "material_type_id" => $roca->id,
        "zone_id" => $desierto->id,
        "name" => "Obsidiana",
        "description" => "Roca volcánica vítrea",
        "efficiency" => 25
    ],
    [
        "material_type_id" => $roca->id,
        "zone_id" => $pradera->id,
        "name" => "Granito",
        "description" => "Roca ígnea plutónica de textura granular",
        "efficiency" => 36
    ],
    [
        "material_type_id" => $mineral->id,
        "zone_id" => $montanya->id,
        "name" => "Caolinita",
        "description" => "Mineral arcilloso de silicato de aluminio hidratado",
        "efficiency" => 13
    ],
    [
        "material_type_id" => $mineral->id,
        "zone_id" => $desierto->id,
        "name" => "Illita",
        "description" => "Mineral arcilloso del grupo de las micas",
        "efficiency" => 16
    ],
    [
        "material_type_id" => $mineral->id,
        "zone_id" => $desierto->id,
        "name" => "Montmorillonita",
        "description" => "Mineral arcilloso del grupo de las esmectitas",
        "efficiency" => 26
    ],
    [
        "material_type_id" => $mineral->id,
        "zone_id" => $p_sur->id,
        "name" => "Cuarzo",
        "description" => "Mineral compuesto de sílice",
        "efficiency" => 28
    ],
    [
        "material_type_id" => $mineral->id,
        "zone_id" => $montanya->id,
        "name" => "Grafito",
        "description" => "Forma cristalina del carbono",
        "efficiency" => 34
    ],
    [
        "material_type_id" => $mineral->id,
        "zone_id" => $glaciar->id,
        "name" => "Minerales semiconductores",
        "description" => "Materiales con propiedades semiconductoras",
        "efficiency" => 36
    ],
    [
        "material_type_id" => $mineral->id,
        "zone_id" => $lagos->id,
        "name" => "Cristales naturales",
        "description" => "Minerales cristalinos",
        "efficiency" => 37
    ],
    [
        "material_type_id" => $mineral->id,
        "zone_id" => $glaciar->id,
        "name" => "Materiales magnéticos naturales",
        "description" => "Materiales con propiedades magnéticas",
        "efficiency" => 39
    ],
    [
        "material_type_id" => $arena->id,
        "zone_id" => $pradera->id,
        "name" => "Arena de sílice",
        "description" => "Arena compuesta principalmente por dióxido de silicio",
        "efficiency" => 14
    ],
    [
        "material_type_id" => $arena->id,
        "zone_id" => $p_norte->id,
        "name" => "Arena de cuarzo",
        "description" => "Arena formada por cristales de cuarzo",
        "efficiency" => 38
    ],
    [
        "material_type_id" => $arena->id,
        "zone_id" => $lagos->id,
        "name" => "Arena de playa",
        "description" => "Arena natural encontrada en playas",
        "efficiency" => 23
    ],
    [
        "material_type_id" => $metal->id,
        "zone_id" => $p_sur->id,
        "name" => "Hierro",
        "description" => "Metal extraído de la hematita y magnetita",
        "efficiency" => 12
    ],
    [
        "material_type_id" => $metal->id,
        "zone_id" => $montanya->id,
        "name" => "Cobre",
        "description" => "Metal extraído de la calcopirita y malaquita",
        "efficiency" => 21
    ],
    [
        "material_type_id" => $metal->id,
        "zone_id" => $bosque->id,
        "name" => "Estaño",
        "description" => "Metal extraído de la casiterita",
        "efficiency" => 25
    ],
    [
        "material_type_id" => $metal->id,
        "zone_id" => $selva->id,
        "name" => "Plata",
        "description" => "Metal precioso extraído de la argentita",
        "efficiency" => 36
    ],
    [
        "material_type_id" => $metal->id,
        "zone_id" => $p_norte->id,
        "name" => "Oro",
        "description" => "Metal precioso en estado nativo",
        "efficiency" => 40
    ],
    [
        "material_type_id" => $metal->id,
        "zone_id" => $glaciar->id,
        "name" => "Plomo",
        "description" => "Metal extraído de la galena",
        "efficiency" => 27
    ],
    [
        "material_type_id" => $madera->id,
        "zone_id" => $montanya->id,
        "name" => "Roble",
        "description" => "Madera dura de árbol de roble",
        "efficiency" => 38
    ],
    [
        "material_type_id" => $madera->id,
        "zone_id" => $glaciar->id,
        "name" => "Pino",
        "description" => "Madera de árbol de pino",
        "efficiency" => 13
    ],
    [
        "material_type_id" => $madera->id,
        "zone_id" => $glaciar->id,
        "name" => "Cedro",
        "description" => "Madera de árbol de cedro",
        "efficiency" => 25
    ],
    [
        "material_type_id" => $fibra->id,
        "zone_id" => $lagos->id,
        "name" => "Cáñamo",
        "description" => "Fibra natural de la planta de cannabis",
        "efficiency" => 11
    ],
    [
        "material_type_id" => $fibra->id,
        "zone_id" => $p_sur->id,
        "name" => "Lino",
        "description" => "Fibra natural de la planta de lino",
        "efficiency" => 30
    ],
    [
        "material_type_id" => $fibra->id,
        "zone_id" => $montanya->id,
        "name" => "Yute",
        "description" => "Fibra natural de la planta de yute",
        "efficiency" => 27
    ],
    [
        "material_type_id" => $fibra->id,
        "zone_id" => $bosque->id,
        "name" => "Caña común",
        "description" => "Tallo de la planta de caña común",
        "efficiency" => 13
    ],
    [
        "material_type_id" => $fibra->id,
        "zone_id" => $glaciar->id,
        "name" => "Totora",
        "description" => "Planta acuática utilizada como material",
        "efficiency" => 34
    ],
    [
        "material_type_id" => $fibra->id,
        "zone_id" => $glaciar->id,
        "name" => "Carrizo",
        "description" => "Planta gramínea utilizada como material",
        "efficiency" => 26
    ],
    [
        "material_type_id" => $fibra->id,
        "zone_id" => $p_sur->id,
        "name" => "Bambú",
        "description" => "Planta gramínea de tallo leñoso",
        "efficiency" => 35
    ],
    [
        "material_type_id" => $fibra->id,
        "zone_id" => $bosque->id,
        "name" => "Algodón",
        "description" => "Fibra natural de la planta de algodón",
        "efficiency" => 18
    ],
    [
        "material_type_id" => $fibra->id,
        "zone_id" => $selva->id,
        "name" => "Lana",
        "description" => "Fibra natural animal",
        "efficiency" => 12
    ],
    [
        "material_type_id" => $resina->id,
        "zone_id" => $pradera->id,
        "name" => "Ámbar",
        "description" => "Resina fósil",
        "efficiency" => 30
    ],
    [
        "material_type_id" => $resina->id,
        "zone_id" => $montanya->id,
        "name" => "Goma arábiga",
        "description" => "Resina natural de árbol de acacia",
        "efficiency" => 15
    ],
    [
        "material_type_id" => $resina->id,
        "zone_id" => $p_norte->id,
        "name" => "Látex",
        "description" => "Resina natural de árbol de caucho",
        "efficiency" => 27
    ],
    [
        "material_type_id" => $resina->id,
        "zone_id" => $montanya->id,
        "name" => "Resinas inflamables",
        "description" => "Resinas naturales combustibles",
        "efficiency" => 34
    ],
    [
        "material_type_id" => $organico->id,
        "zone_id" => $selva->id,
        "name" => "Pieles",
        "description" => "Material orgánico animal",
        "efficiency" => 28
    ],
    [
        "material_type_id" => $organico->id,
        "zone_id" => $bosque->id,
        "name" => "Huesos",
        "description" => "Material orgánico animal",
        "efficiency" => 25
    ],
    [
        "material_type_id" => $organico->id,
        "zone_id" => $lagos->id,
        "name" => "Tendones",
        "description" => "Tejido conectivo animal",
        "efficiency" => 42
    ],
    [
        "material_type_id" => $organico->id,
        "zone_id" => $p_sur->id,
        "name" => "Cuero",
        "description" => "Piel animal procesada",
        "efficiency" => 32
    ],
    [
        "material_type_id" => $organico->id,
        "zone_id" => $bosque->id,
        "name" => "Plumas",
        "description" => "Material orgánico animal",
        "efficiency" => 14
    ],
    [
        "material_type_id" => $organico->id,
        "zone_id" => $p_norte->id,
        "name" => "Carbón natural",
        "description" => "Material orgánico fosilizado",
        "efficiency" => 39
    ]
];
