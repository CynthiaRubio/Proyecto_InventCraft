<?php

namespace App\Services;

use App\Models\Action;
use App\Models\Stat;
use App\Models\UserStat;
use App\Models\User;
use App\Models\Zone;
use App\Models\Invention;
use App\Models\Building;
use App\Models\ActionType;
use App\Models\Material;
use App\Models\ActionZone;
use App\Models\Resource;
use App\Models\InventoryMaterial;
use App\Models\Event;

class ResourceManagementService
{
    public function __construct(
        private UserManagementService $user_service,
        private ActionManagementService $action_service,
        private InventionService $invention_service,
    ) {
    }

    /**
     * Calcula los recursos obtenidos al explorar una zona
     */
    public function calculateFarm(string $zone_id, $time)
    {
        $user_id = auth()->user()->id;
        $zone = $this->action_service->getZoneWithRelations($zone_id);

        $suerte_user = $this->user_service->getUserStat('Suerte');

        $farm_result = $this->calculateResources($zone, $suerte_user, $time);

        $action_type_id = ActionType::where('name', 'Mover')->first()->id;

        /* Buscamos la última acción de mover del usuario */
        $action_id = Action::where('user_id', $user_id)
                    ->where('action_type_id', $action_type_id)
                    ->where('actionable_type', 'App\Models\Zone')
                    ->latest()->first();//->id;

        /* Buscamos el ActionZone creado cuando el usuario se ha movido a esa zona */
        $action_zone = ActionZone::where('action_id', $action_id->id)
                            ->where('zone_id', $action_id->actionable_id)
                            ->latest()->first();


        /* Creamos la acción de recolectar */
        /* TO DO Cambiar, el actionable_type es Zone y el actionable_id es el id de la zona */
        $this->action_service->createAction('Recolectar', $action_zone->_id, 'ActionZone', $time);

        $multiplier = $this->generateEvents($zone_id);

        // $events = Event::where('zone_id', $zone_id)->get();

        // $event_probability = rand(0, 2);
        // /* Ocurren eventos 1 de cada 3 veces */
        // if ($event_probability == 2) {
        //     $event_type = $events->random();
        //     $loss_percent = rand(0, 100);
        //     $multiplier = (100 - $loss_percent) / 100;
        // } else {
        //     $multiplier = 1;
        // }

        /* Asignar materiales obtenidos al inventario del jugador cuando accion este terminada */

        if ($farm_result) {

            foreach ($farm_result as $resource) {
                if ($resource['type'] === 'Material') {
                    $resourceable_type = 'App\Models\Material';
                    /* LLamar a guardar materiales */
                    $quantity = round($resource['quantity'] * $multiplier);
                    $this->saveMaterials($resource['id'], $quantity);
                } elseif ($resource['type'] === 'Invention') {
                    $resourceable_type = 'App\Models\Invention';
                } else {
                    continue;
                }

                $resource = Resource::create([
                    'action_zone_id' => $action_zone->_id,
                    'resourceable_id' => $resource['id'],
                    'resourceable_type' => $resourceable_type,
                    'quantity' => $resource['quantity'],
                ]);
            }
        }

        return $farm_result;
    }

    /**
     * Calcula los recursos disponibles en una zona según la suerte del usuario
     */
    public function calculateResources($zone, int $suerte_user, int $time)
    {
        $result_materials = [];

        foreach ($zone->materials as $material) {

            $probability = $this->calculateMaterialProbability($material->efficiency, $suerte_user, $time);

            if ($probability) {
                $quantity = $this->calculateMaterialQuantity($material->efficiency);


                if ($quantity !== 0) {
                    $result_materials[] = [
                        'type' => 'Material',
                        'id' => $material->_id,
                        'quantity' => $quantity,
                    ];

                }
            }
        }
        $result_inventions = $this->calculateInventions($zone, $suerte_user, $time);
        $result = array_merge($result_materials, $result_inventions);

        return $result;
    }

    /**
     * Calcula la probabilidad de obtener un material
     */
    public function calculateMaterialProbability(int $efficiency, int $luck, int $time)
    {
        $probabilidad = min((50 - $efficiency + $luck + ($time / 30)), 100);

        if ($probabilidad >= rand(0, 100)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determina la cantidad de un material basado en su eficiencia
     */
    public function calculateMaterialQuantity(int $efficiency)
    {
        return match (true) {
            $efficiency <= 22 => rand(1, 9),
            $efficiency > 22 && $efficiency <= 30 => rand(1, 6),
            $efficiency > 30 => rand(1, 3),
            default => 0,
        };
    }

    /**
     * Calcula la probabilidad de encontrar un invento en la zona
     */
    public function calculateInventions($zone, int $luck, int $time)
    {
        $probability = rand(0, 100) + $luck + ($time / 30);
        $inventions = [];

        if ($probability >= 85) {
            $num_inventions = 3;
        } elseif ($probability >= 60) {
            $num_inventions = 2;
        } elseif ($probability >= 40) {
            $num_inventions = 1;
        } else {
            $num_inventions = 0;
        }

        for ($i = 0; $i < $num_inventions; $i++) {
            $invention_type = $zone->inventionTypes->random();

            $materials = Material::where('material_type_id', $invention_type->material_type_id)->get();
            $material_id = $materials->random()->id;

            $invention_created_id = $this->invention_service->createInvention($invention_type->id, $material_id, 0)->id;
            $inventions[] = [
                'type' => 'Invention',
                'id' => $invention_created_id,
                'quantity' => 1,
            ];
        }

        return $inventions;
    }

    /**
     * Función que guarda los materiales en el inventario recibiendo:
     *
     * @param $material_id: El id del material a guardar
     * @param $quantity: La cantidad de material
     */
    public function saveMaterials(string $material_id, int $quantity)
    {

        $save = false;
        $inventory = $this->user_service->getUserInventory();

        /* Comprobamos si el jugador ya tiene el material en el inventario */
        $inventoryMaterial = InventoryMaterial::where('inventory_id', $inventory->id)
                                              ->where('material_id', $material_id)
                                              ->first();

        /* Si existe se añade la cantidad no disponible */
        if ($inventoryMaterial) {
            $inventoryMaterial->increment('quantity_na', $quantity);
            $save = true;
            /* Sino, se guarda pero sin poder disponer de la cantidad */
        } else {
            InventoryMaterial::create([
                'inventory_id' => $inventory->id,
                'material_id' => $material_id,
                'quantity' => 0,
                'quantity_na' => $quantity,
            ]);
            $save = true;
        }

        return $save;
    }

    /**
     * Función para saber la pérdida de materiales por evento en zona
     */
    public function generateEvents($zone_id)
    {
        $events = Event::where('zone_id', $zone_id)->get();

        $event_probability = rand(0, 2);
        /* Ocurren eventos 1 de cada 3 veces */
        if ($event_probability == 2) {
            $event_type = $events->random();
            $loss_percent = rand(0, 100);
            return (100 - $loss_percent) / 100;
        } else {
           return 1;
        }
    }

}
