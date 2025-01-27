<?php

use App\Models\InventionType;

$carro = InventionType::where('name', 'Carro')->value('id');
$rueda = InventionType::where('name', 'Rueda')->value('id');
$barco = InventionType::where('name', 'Barco')->value('id');
$piedraAfilada = InventionType::where('name', 'Piedra Afilada')->value('id');
$cuerda = InventionType::where('name', 'Cuerda')->value('id');
$lanza = InventionType::where('name', 'Lanza')->value('id');
$arcoFlecha = InventionType::where('name', 'Arco Flecha')->value('id');
$hacha = InventionType::where('name', 'Hacha')->value('id');
$cesta = InventionType::where('name', 'Cesta')->value('id');
$torno = InventionType::where('name', 'Torno')->value('id');
$agricultura = InventionType::where('name', 'Agricultura')->value('id');
$ganaderia = InventionType::where('name', 'Ganadería')->value('id');
$arado = InventionType::where('name', 'Arado')->value('id');
$trampa = InventionType::where('name', 'Trampa')->value('id');
$riegoAutomatizado = InventionType::where('name', 'Riego Automatizado')->value('id');
$fuego = InventionType::where('name', 'Fuego')->value('id');
$canal = InventionType::where('name', 'Canal')->value('id');
$metalurgia = InventionType::where('name', 'Metalurgia')->value('id');
$vidrio = InventionType::where('name', 'Vidrio')->value('id');
$ceramica = InventionType::where('name', 'Cerámica')->value('id');
$alfareria = InventionType::where('name', 'Alfarería')->value('id');
$tela = InventionType::where('name', 'Tela')->value('id');
$acueducto = InventionType::where('name', 'Acueducto')->value('id');
$molino = InventionType::where('name', 'Molino')->value('id');
$horno = InventionType::where('name', 'Horno')->value('id'); 


return [
    [
        'invention_type_id' => $carro,
        'invention_type_need_id' => $rueda,
        'quantity' => 2,
    ],
    [
        'invention_type_id' => $carro,
        'invention_type_need_id' => $cesta,
        'quantity' => 1,
    ],
    [
        'invention_type_id' => $carro,
        'invention_type_need_id' => $hacha,
        'quantity' => 1,
    ],
    [
        'invention_type_id' => $lanza,
        'invention_type_need_id' => $piedraAfilada,
        'quantity' => 1,
    ],
    [
        'invention_type_id' => $lanza,
        'invention_type_need_id' => $cuerda,
        'quantity' => 1,
    ],
    [
        'invention_type_id' => $arcoFlecha,
        'invention_type_need_id' => $lanza,
        'quantity' => 1,
    ],
    [
        'invention_type_id' => $arcoFlecha,
        'invention_type_need_id' => $cuerda,
        'quantity' => 1,
    ],
    [
        'invention_type_id' => $trampa,
        'invention_type_need_id' => $cesta,
        'quantity' => 1,
    ],
    [
        'invention_type_id' => $trampa,
        'invention_type_need_id' => $arcoFlecha,
        'quantity' => 1,
    ],
    [
        'invention_type_id' => $hacha,
        'invention_type_need_id' => $piedraAfilada,
        'quantity' => 1,
    ],
    [
        'invention_type_id' => $ganaderia,
        'invention_type_need_id' => $cuerda,
        'quantity' => 3,
    ],
    [
        'invention_type_id' => $ganaderia,
        'invention_type_need_id' => $piedraAfilada,
        'quantity' => 3,
    ],
    [
        'invention_type_id' => $ganaderia,
        'invention_type_need_id' => $trampa,
        'quantity' => 1,
    ],
    [
        'invention_type_id' => $ceramica,
        'invention_type_need_id' => $fuego,
        'quantity' => 1,
    ],
    [
        'invention_type_id' => $ceramica,
        'invention_type_need_id' => $cesta,
        'quantity' => 1,
    ],
    [
        'invention_type_id' => $alfareria,
        'invention_type_need_id' => $ceramica,
        'quantity' => 1,
    ],
    [
        'invention_type_id' => $alfareria,
        'invention_type_need_id' => $fuego,
        'quantity' => 1,
    ],
    [
        'invention_type_id' => $torno,
        'invention_type_need_id' => $alfareria,
        'quantity' => 1,
    ],
    [
        'invention_type_id' => $torno,
        'invention_type_need_id' => $rueda,
        'quantity' => 1,
    ],
    [
        'invention_type_id' => $agricultura,
        'invention_type_need_id' => $ganaderia,
        'quantity' => 1,
    ],
    [
        'invention_type_id' => $agricultura,
        'invention_type_need_id' => $lanza,
        'quantity' => 6,
    ],
    [
        'invention_type_id' => $agricultura,
        'invention_type_need_id' => $cesta,
        'quantity' => 3,
    ],[
        'invention_type_id' => $arado,
        'invention_type_need_id' => $rueda,
        'quantity' => 2,
    ],
    [
        'invention_type_id' => $arado,
        'invention_type_need_id' => $agricultura,
        'quantity' => 1,
    ],
    [
        'invention_type_id' => $arado,
        'invention_type_need_id' => $hacha,
        'quantity' => 1,
    ],
    [
        'invention_type_id' => $tela,
        'invention_type_need_id' => $cuerda,
        'quantity' => 6,
    ],
    [
        'invention_type_id' => $tela,
        'invention_type_need_id' => $ganaderia,
        'quantity' => 1,
    ],
    [
        'invention_type_id' => $tela,
        'invention_type_need_id' => $torno,
        'quantity' => 2,
    ],
    [
        'invention_type_id' => $barco,
        'invention_type_need_id' => $tela,
        'quantity' => 1,
    ],
    [
        'invention_type_id' => $barco,
        'invention_type_need_id' => $cuerda,
        'quantity' => 3,
    ],
    [
        'invention_type_id' => $barco,
        'invention_type_need_id' => $hacha,
        'quantity' => 1,
    ],
    [
        'invention_type_id' => $barco,
        'invention_type_need_id' => $carro,
        'quantity' => 1,
    ],
    [
        'invention_type_id' => $vidrio,
        'invention_type_need_id' => $fuego,
        'quantity' => 1,
    ],
    [
        'invention_type_id' => $vidrio,
        'invention_type_need_id' => $ceramica,
        'quantity' => 2,
    ],
    [
        'invention_type_id' => $metalurgia,
        'invention_type_need_id' => $fuego,
        'quantity' => 1,
    ],
    [
        'invention_type_id' => $metalurgia,
        'invention_type_need_id' => $ceramica,
        'quantity' => 2,
    ],
    [
        'invention_type_id' => $metalurgia,
        'invention_type_need_id' => $piedraAfilada,
        'quantity' => 3,
    ],
    [
        'invention_type_id' => $molino,
        'invention_type_need_id' => $rueda,
        'quantity' => 1,
    ],
    [
        'invention_type_id' => $molino,
        'invention_type_need_id' => $hacha,
        'quantity' => 1,
    ],
    [
        'invention_type_id' => $molino,
        'invention_type_need_id' => $metalurgia,
        'quantity' => 1,
    ],
    [
        'invention_type_id' => $molino,
        'invention_type_need_id' => $cuerda,
        'quantity' => 3,
    ],
    [
        'invention_type_id' => $horno,
        'invention_type_need_id' => $metalurgia,
        'quantity' => 1,
    ],
    [
        'invention_type_id' => $horno,
        'invention_type_need_id' => $alfareria,
        'quantity' => 1,
    ],
    [
        'invention_type_id' => $canal,
        'invention_type_need_id' => $horno,
        'quantity' => 1,
    ],
    [
        'invention_type_id' => $canal,
        'invention_type_need_id' => $ceramica,
        'quantity' => 3,
    ],
    [
        'invention_type_id' => $canal,
        'invention_type_need_id' => $fuego,
        'quantity' => 2,
    ],
    [
        'invention_type_id' => $riegoAutomatizado,
        'invention_type_need_id' => $canal,
        'quantity' => 3,
    ],
    [
        'invention_type_id' => $riegoAutomatizado,
        'invention_type_need_id' => $molino,
        'quantity' => 1,
    ],
    [
        'invention_type_id' => $riegoAutomatizado,
        'invention_type_need_id' => $agricultura,
        'quantity' => 1,
    ],
    [
        'invention_type_id' => $riegoAutomatizado,
        'invention_type_need_id' => $arado,
        'quantity' => 1,
    ],
    [
        'invention_type_id' => $acueducto,
        'invention_type_need_id' => $riegoAutomatizado,
        'quantity' => 2,
    ],
    [
        'invention_type_id' => $acueducto,
        'invention_type_need_id' => $canal,
        'quantity' => 10,
    ],
    
];
