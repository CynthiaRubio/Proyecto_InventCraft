<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ActionType;

class ActionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Se especifican los tipos de acciones que puede realizar cada jugador
        $action_types = [
            ['name' => 'Mover', 'description' => 'El usuario se mueve de una zona a otra'],
//El tipo de action recolectar sobraría ¿no?
            ['name' => 'Recolectar', 'description' => 'El usuario explora la zona y recolecta los materiales y/o inventos que encuentra'],
            ['name' => 'Crear', 'description' => 'El usuario crea un invento'],
            ['name' => 'Construir', 'description' => 'El usuario construye un edificio'],
        ];

        //Se recorre el array de acciones para crear cada una
        foreach ($action_types as $type) {
            ActionType::create($type);

            /* Dentro del paréntesis en lugar de $type
                [
                'name' => $type['name'],
                'description' => $type['description'],
                ]
            */
        }
    }
}
