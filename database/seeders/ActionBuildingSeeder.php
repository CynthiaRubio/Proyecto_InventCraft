<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Action;
use App\Models\ActionBuilding;
use App\Models\Building;

class ActionBuildingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /* Primero se traen todos los edificios */
        $buildings = Building::all();

        /* Ahora se traen todas las acciones cuyo tipo sea building */
        $actions = Action::where('actionable_type', Building::class)->get();

        /* Se recorren los edificios */
        foreach ($buildings as $building) {
            /* y las acciones */
            foreach ($actions as $action) {
                /* Y se crean los valores de estas tablas cogiendo los id de los elementos recorridos */
                ActionBuilding::create([
                    'action_id' => $action->_id,
                    'building_id' => $building->_id,
                    'efficiency' => rand(1, 50),
                ]);
            }
        }
    }
}
