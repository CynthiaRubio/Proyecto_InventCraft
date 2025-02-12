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
use App\Models\MaterialType;
use App\Models\InventionType;
use App\Models\InventionTypeInventionType;

class ResourceManagementService
{
    public function __construct(
        private UserManagementService $user_service,
        private ActionManagementService $action_service,
        private InventionService $invention_service,
        private ZoneManagementService $zone_service,
    ) {
    }

    /**
     * Calcula los recursos obtenidos al explorar una zona
     */
    public function calculateFarm(string $zone_id, $time, $action)
    {
        $action_zone = $this->action_service->createActionZone($action);
        $multiplier = $this->generateEvents($zone_id);

        if ($action_zone && $multiplier !== 0) {

            $farm_result = [];

            $zone = $this->zone_service->getZoneWithRelations($zone_id);

            $suerte_user = $this->user_service->getUserStat('Suerte');

            $farm_results = $this->calculateResources($zone, $suerte_user, $time, $multiplier);

            if (count($farm_results) > 0) {
                $this->saveResources($farm_results, $action_zone);
                return $farm_results;
            } else {
                return 0;
            }
        }
    }

    /**
     * Obtiene los recursos recolectados en una zona según la suerte del usuario
     */
    public function calculateResources($zone, int $suerte_user, int $time, float $multiplier)
    {
        $result_materials = $this->farmMaterials($zone, $suerte_user, $time, $multiplier);

        $result_inventions = $this->farmInventions($zone, $suerte_user, $time, $multiplier);

        $results = array_merge($result_materials, $result_inventions);

        return $results;
    }

    /**
     * Recolecta los materiales en la exploracion
     */
    public function farmMaterials($zone, int $suerte_user, int $time, float $multiplier)
    {

        $result_materials = [];
        

        foreach ($zone->materials as $material) {
            
            $probability = $this->calculateMaterialProbability($material->efficiency, $suerte_user, $time);

            if ($probability) {

                $quantity = round($this->calculateMaterialQuantity($material->efficiency) * $multiplier);
                
                if ($quantity > 0) {
                    array_push( $result_materials, [
                        'type' => 'Material',
                        'id' => $material->_id,
                        'quantity' => $quantity,
                    ] );
                    
                }
            }
        }
        
        return $result_materials;
    }


    /**
     * Calcula la probabilidad de obtener un material
     */
    public function calculateMaterialProbability(int $efficiency, int $suerte_user, int $time)
    {
        $probabilidad = min((50 - $efficiency + $suerte_user + ($time / 30)), 100);

        if ($probabilidad >= rand(0, 70)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determina la cantidad encontrada de un material basado en su eficiencia
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
    public function farmInventions($zone, int $suerte_user, int $time, $multiplier)
    {
        $num_inventions = round($this->calculateNumberInvention($suerte_user, $time) * $multiplier);

        $inventions = [];

        for ($i = 0; $i < $num_inventions; $i++) {
            $invention_type = $zone->inventionTypes->random();

            $materials = Material::where('material_type_id', $invention_type->material_type_id)->get();
            $material_id = $materials->random()->id;

            $invention_created_id = $this->invention_service->createInvention($invention_type->id, $material_id, 0)->id;
            array_push( $inventions,  [
                'type' => 'Invention',
                'id' => $invention_created_id,
                'quantity' => 1,
            ] );
        }

        return $inventions;
    }

    /**
     * Calcula el número de inventos encontrados durante la exploración
     */
    public function calculateNumberInvention(int $suerte_user, int $time)
    {
        $probability = min((50 + $suerte_user + ($time / 30)), 100);

        if ($probability >= 85) {
            $num_inventions = 3;
        } elseif ($probability >= 60) {
            $num_inventions = 2;
        } elseif ($probability >= 40) {
            $num_inventions = 1;
        } else {
            $num_inventions = 0;
        }

        return $num_inventions;
    }


    /**
     * Guarda los recursos obtenidos en el inventario del jugador
     */
    public function saveResources($results, $action_zone)
    {

        foreach ($results as $resource) {

            if ($resource['type'] === 'Material') {
                $resourceable_type = 'App\Models\Material';
                $this->saveMaterials($resource);
            } elseif ($resource['type'] === 'Invention') {
                $resourceable_type = 'App\Models\Invention';
                /* No hay que guardar los inventos porque se asocian al inventario cuando se crean pero no disponibles */
            } else {
                continue;
            }

            $resource_created = Resource::create([
                'action_zone_id' => $action_zone->_id,
                'resourceable_id' => $resource['id'],
                'resourceable_type' => $resourceable_type,
                'quantity' => $resource['quantity'],
                'available' => false,
            ]);

        }

    }

    /**
     * Guarda los materiales en el inventario
     *
     * @param $resource: El recurso obtenido
     */
    public function saveMaterials($resource)
    {
        $save = false;
        $inventory = $this->user_service->getUserInventory();

        /* Comprobamos si el jugador ya tiene el material en el inventario */
        $inventoryMaterial = InventoryMaterial::where('inventory_id', $inventory->_id)
                                              ->where('material_id', $resource['id'])
                                              ->first();

        /* Si existe se añade la cantidad no disponible */
        if ($inventoryMaterial) {

            $inventoryMaterial->update(['quantity_na' => $resource['quantity']]);
            $save = true;

            /* Sino, se crea en el inventario pero sin poder disponer de la cantidad */
        } else {
            InventoryMaterial::create([
                'inventory_id' => $inventory->id,
                'material_id' => $resource['id'],
                'quantity' => 0,
                'quantity_na' => $resource['quantity'],
            ]);
            $save = true;
        }

        return $save;
    }

    /**
     * Actualiza los recursos y los prepara para mostrarlo
     */
    public function updateResources($action)
    {

                $inventory_id = $this->user_service->getUserInventory()->id;

                $action_zone = $this->action_service->getActionZone($action);

                $results = $this->recolectResourcesNoAvailables($action_zone);

                if (count($results) > 0) {

                    $farm_result = [];

                    foreach ($results as $result) {
                        if ($result->resourceable_type === 'App\Models\Material') {

                            $material = InventoryMaterial::where('inventory_id', $inventory_id)
                                ->where('material_id', $result->resourceable_id)
                                ->first();
                            $quantity = $material->quantity + $material->quantity_na;

                            $material_details = Material::where('id', $material->material_id)->first();

                            array_push($farm_result, [$material_details->name => $material->quantity_na]);

                            $material->update(['quantity' => $quantity , 'quantity_na' => 0]);
    
                        } elseif ($result->resourceable_type === 'App\Models\Invention') {
                            $invention = Invention::where('id', $result->resourceable_id)->first();
                            $invention->update(['available' => true]);
                            array_push($farm_result, [$invention->name => 1]);
                        }

                    }

                } else {
                    $farm_result = "Ohhh los eventos de esta zona no te han permitdo no encontrar nada en esta exploración ¡Inténtalo de nuevo! seguro que el evento ocurrido ha terminado.";
                }

                $this->updateResourcesNoAvailables($action_zone);
                return $farm_result;
    }

    /**
     * Obtiene los recursos no disponibles, que son los últimos recogidos
     */
    public function recolectResourcesNoAvailables($action_zone)
    {
            $results = Resource::where('action_zone_id', $action_zone->_id)
                        ->where('available', false)->get();
  
            return $results;
    }

    public function updateResourcesNoAvailables($action_zone)
    {
        Resource::where('action_zone_id', $action_zone->_id)
                    ->where('available', false)->update(['available' => true]);
    }

    /**
     * Obtiene los inventos del inventario del jugador agrupados por tipo
     */
    public function getUserInventionsByType()
    {
        $inventory_user = $this->user_service->getUserInventoryWithRelations();

        $user_inventions_by_type = $inventory_user->inventions->groupBy('invention_type_id');

        return $user_inventions_by_type;
    }

    /**
     * Obtiene los inventos del inventario del jugador agrupados por tipo
     */
    public function getUserInventionsByTypeWithoutRelations()
    {
        $inventory_user = $this->user_service->getUserInventory();

        // $user_inventions_by_type = $inventory_user->inventions->groupBy('invention_type_id');
        $user_inventions_by_type = Invention::where('inventory_id', $inventory_user->_id)->get()->groupBy('invention_type_id');
        return $user_inventions_by_type;
    }

    /**
     * Comprueba que el jugador posee los inventos necesarios para construir un edificio
     */
    public function checkInventionsToConstruct($invention_types_needed, $num_needed, $user_inventions_by_type)
    {
        foreach ($invention_types_needed as $type) {

            if (count($user_inventions_by_type[$type->id]) < $num_needed) {
                return redirect()->back()
                    ->with('error', "No tienes suficientes inventos de tipo {$type->name}. Se requieren {$num_needed}.");
            }
        }
        return true;
    }

    /**
     * Comprueba que el jugador posee los inventos necesarios para crear un invento
     */
    public function checkInventionsToCreate($invention_types_needed, $user_inventions_by_type)
    {

        foreach($invention_types_needed as $needed_type){
            foreach($user_inventions_by_type as $type => $arrayInventions){
                if($needed_type->invention_type_need_id === $type){
                    if(count($arrayInventions) < $needed_type->quantity){
                        return redirect()->back()
                            ->with('error', "No tienes suficientes inventos de tipo {$needed_type->invention_type_need_id}.");
                    }
                }
            }
        }

        return true;
    }

    /**
     * Obtiene los materiales del inventario del jugador
     */
    public function getUserMaterialsByType($material_type_id)
    {
        $inventory_user = $this->user_service->getUserInventoryWithRelations();
        
        $materials_user = $inventory_user->materials->where('material.material_type_id', $material_type_id);
        return $materials_user;

    }

    /**
     * Comprueba que el jugador posee los materiales necesarios para crear un invento
     */
    public function checkMaterials($user_materials, $name)
    {
        if (count($user_materials) <= 0) {
            return redirect()->back()->with('error', "No tienes materiales de tipo {$name}");
        }

        return true;
    }


    /**
     * Reduce la cantidad que hay en el inventario del material utilizado en la creación de inventos 
     */
    public function decrementMaterial($material_id)
    {

        $inventory = $this->user_service->getUserInventory();
        $material = InventoryMaterial::where('inventory_id', $inventory->_id)
            ->where('material_id', $material_id)->first();

        if ($material->quantity > 1) {
            $new_quantity = $material->quantity - 1;
            $material->update(['quantity' => $new_quantity]);
        } else {
            $material->delete();
        }

        return true;
    }



    /**
     * Calcula el porcentaje de pérdida de recursos por evento durante la exploración
     */
    public function generateEvents($zone_id)
    {
        /* TODO Habría que establecer la pérdida por evento y no de forma independiente como está */
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
