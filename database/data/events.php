<?php

use App\Models\Zone;

$pradera = Zone::where('name', 'Pradera')->first();
$bosque = Zone::where('name', 'Bosque')->first();
$selva = Zone::where('name', 'Selva')->first();
$desierto = Zone::where('name', 'Desierto')->first();
$montanya = Zone::where('name', 'Montaña')->first();
$polo_norte = Zone::where('name', 'Polo Norte')->first();
$glaciar = Zone::where('name', 'Glaciar de Montaña')->first();
$lagos = Zone::where('name', 'Lagos')->first();
$polo_sur = Zone::where('name', 'Polo Sur')->first();

return [
    [
        'zone_id' => $pradera->id,
        'name' => 'Sequía',
        'description' => 'Un período prolongado sin lluvias que afecta el crecimiento de las plantas y la disponibilidad de agua.'
    ],
    [
        'zone_id' => $pradera->id,
        'name' => 'Plaga de Insectos',
        'description' => 'Una infestación de insectos que puede devastar los cultivos y afectar la producción agrícola.'
    ],
    [
        'zone_id' => $bosque->id,
        'name' => 'Incendio Forestal',
        'description' => 'Un incendio que se propaga rápidamente a través del bosque, afectando la flora y fauna.'
    ],
    [
        'zone_id' => $bosque->id,
        'name' => 'Tormenta de Viento',
        'description' => 'Fuertes vientos que pueden derribar árboles y causar daños a las estructuras.'
    ],
    [
        'zone_id' => $selva->id,
        'name' => 'Inundación',
        'description' => 'Lluvias intensas que causan el desbordamiento de ríos y arroyos, inundando grandes áreas de la selva.'
    ],
    [
        'zone_id' => $selva->id,
        'name' => 'Deslizamiento de Tierra',
        'description' => 'Debido a la alta humedad y las lluvias, pueden ocurrir deslizamientos de tierra que arrasan con la vegetación y estructuras.'
    ],
    [
        'zone_id' => $desierto->id,
        'name' => 'Tormenta de Arena',
        'description' => 'Fuertes vientos que levantan grandes cantidades de arena, reduciendo la visibilidad y causando daños.'
    ],
    [
        'zone_id' => $desierto->id,
        'name' => 'Ola de Calor',
        'description' => 'Temperaturas extremadamente altas que pueden afectar la salud de las personas y los animales, así como dañar las estructuras.'
    ],
    [
        'zone_id' => $montanya->id,
        'name' => 'Avalancha',
        'description' => 'Una gran masa de nieve, hielo y rocas que se desliza por la ladera de una montaña, causando destrucción en su camino.'
    ],
    [
        'zone_id' => $montanya->id,
        'name' => 'Desprendimiento de Rocas',
        'description' => 'Rocas que se desprenden de las laderas de las montañas y caen, causando daños a las estructuras y caminos.'
    ],
    [
        'zone_id' => $polo_norte->id,
        'name' => 'Tormenta de Nieve',
        'description' => 'Fuertes nevadas acompañadas de vientos intensos que pueden causar acumulaciones de nieve y reducir la visibilidad.'
    ],
    [
        'zone_id' => $polo_norte->id,
        'name' => 'Deshielo',
        'description' => 'El derretimiento de grandes masas de hielo debido a temperaturas más cálidas, lo que puede causar inundaciones y afectar la fauna local.'
    ],
    [
        'zone_id' => $glaciar->id,
        'name' => 'Desprendimiento de glaciares',
        'description' => 'Los desprendimientos dañan la zona y dificultan la búsqueda de recursos',
    ],
    [
        'zone_id' => $glaciar->id,
        'name' => 'Congelación de recursos',
        'description' => 'El frío extremo congela tus recursos, y hace que tardes más en recolectarlos'
    ],
    [
        'zone_id' => $lagos->id,
        'name' => 'Desbordamiento del lago',
        'description' => 'Las aguas desbordadas arrasan con las cosechas y destruyen construcciones cercanas al agua.'
    ],
    [
        'zone_id' => $lagos->id,
        'name' => 'Contaminación del agua',
        'description' => 'La contaminación reduce la calidad de los recursos pesqueros y afecta la salud de los jugadores.',
    ],

    [
        'zone_id' => $polo_sur->id,
        'name' => 'Tormenta polar',
        'description' => 'La tormenta extrema afecta la movilidad y reduce la recolección de recursos en la zona.',
    ],
    [
        'zone_id' => $polo_sur->id,
        'name' => 'Desplome de icebergs',
        'description' => 'El deshielo provoca el colapso de rutas comerciales y el daño de estructuras cercanas al mar.',
    ],
];


