<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ResourceServiceInterface;
use App\Contracts\UserServiceInterface;
use App\Contracts\ActionServiceInterface;
use App\Contracts\InventionServiceInterface;
use App\Contracts\ZoneServiceInterface;
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
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Http\RedirectResponse;

class ResourceService implements ResourceServiceInterface
{
    public function __construct(
        private UserServiceInterface $userService,
        private ActionServiceInterface $actionService,
        private InventionServiceInterface $inventionService,
        private ZoneServiceInterface $zoneService,
    ) {
    }

    /**
     * Prepara la acción de recolección: genera eventos y crea el ActionZone.
     * 
     * Los recursos NO se calculan aquí, sino cuando finaliza la acción.
     * Solo se determina si ocurre un evento y se guarda el event_id en ActionZone.
     * 
     * @param string $zone_id ID de la zona a explorar
     * @param int $time Tiempo dedicado a la exploración en minutos
     * @param Action $action Acción de recolección creada
     * @return ActionZone|null ActionZone creado o null si hay error
     */
    public function calculateFarm(string $zone_id, int $time, Action $action): ?ActionZone
    {
        $eventResult = $this->generateEvents($zone_id);
        $event = $eventResult['event'];
        
        $event_id = $event ? $event->id : null;
        $action_zone = $this->actionService->createActionZone($action, $event_id);

        return $action_zone;
    }

    /**
     * Obtiene los recursos recolectados en una zona según la suerte del usuario
     * 
     * @param Zone $zone Zona a explorar
     * @param int $suerte_user Valor de la estadística "Suerte" del usuario
     * @param int $time Tiempo dedicado a la exploración
     * @param float $multiplier Multiplicador de recursos (afectado por eventos)
     * @return array Array de recursos encontrados (materiales e inventos)
     */
    public function calculateResources(Zone $zone, int $suerte_user, int $time, float $multiplier): array
    {
        $result_materials = $this->farmMaterials($zone, $suerte_user, $time, $multiplier);

        $result_inventions = $this->farmInventions($zone, $suerte_user, $time, $multiplier);

        $results = array_merge($result_materials, $result_inventions);

        return $results;
    }

    /**
     * Recolecta los materiales en la exploración
     * 
     * @param Zone $zone Zona a explorar
     * @param int $suerte_user Valor de la estadística "Suerte" del usuario
     * @param int $time Tiempo dedicado a la exploración
     * @param float $multiplier Multiplicador de recursos (afectado por eventos)
     * @return array Array de materiales encontrados con su cantidad
     */
    public function farmMaterials(Zone $zone, int $suerte_user, int $time, float $multiplier): array
    {

        $result_materials = [];
        

        foreach ($zone->materials as $material) {
            
            $probability = $this->calculateMaterialProbability((int) $material->efficiency, $suerte_user, $time);

            if ($probability) {

                $quantity = (int) round($this->calculateMaterialQuantity((int) $material->efficiency) * $multiplier);
                
                if ($quantity > 0) {
                    array_push( $result_materials, [
                        'type' => 'Material',
                        'id' => $material->id,
                        'quantity' => $quantity,
                    ] );
                    
                }
            }
        }
        
        return $result_materials;
    }


    /**
     * Calcula la probabilidad de obtener un material durante la exploración
     * 
     * @param int $efficiency Eficiencia del material (0-100)
     * @param int $suerte_user Valor de la estadística "Suerte" del usuario
     * @param int $time Tiempo dedicado a la exploración
     * @return bool True si se obtiene el material, false en caso contrario
     */
    public function calculateMaterialProbability(int $efficiency, int $suerte_user, int $time): bool
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
     * 
     * @param int $efficiency Eficiencia del material (0-100)
     * @return int Cantidad de material encontrado (1-9 si efficiency <= 22, 1-6 si <= 30, 1-3 si > 30)
     */
    public function calculateMaterialQuantity(int $efficiency): int
    {
        return match (true) {
            $efficiency <= 22 => rand(1, 9),
            $efficiency > 22 && $efficiency <= 30 => rand(1, 6),
            $efficiency > 30 => rand(1, 3),
            default => 0,
        };
    }

    /**
     * Calcula los inventos encontrados durante la exploración de una zona.
     * 
     * Los inventos NO se crean aquí, solo se calcula qué inventos se encontrarán.
     * Se crearán cuando finalice la acción con available=true.
     * 
     * @param Zone $zone Zona a explorar
     * @param int $suerte_user Valor de la estadística "Suerte" del usuario
     * @param int $time Tiempo dedicado a la exploración
     * @param float $multiplier Multiplicador de recursos (afectado por eventos)
     * @return array Array con información de los inventos que se encontrarán
     */
    public function farmInventions(Zone $zone, int $suerte_user, int $time, float $multiplier): array
    {
        $num_inventions = round($this->calculateNumberInvention($suerte_user, $time) * $multiplier);

        $inventions = [];

        for ($i = 0; $i < $num_inventions; $i++) {
            $invention_type = $zone->inventionTypes->random();

            $materials = Material::where('material_type_id', $invention_type->material_type_id)->get();
            $material_id = $materials->random()->id;

            $inventions[] = [
                'type' => 'Invention',
                'invention_type_id' => $invention_type->id,
                'material_id' => $material_id,
                'quantity' => 1,
            ];
        }

        return $inventions;
    }

    /**
     * Calcula el número de inventos encontrados durante la exploración
     * 
     * @param int $suerte_user Valor de la estadística "Suerte" del usuario
     * @param int $time Tiempo dedicado a la exploración
     * @return int Número de inventos encontrados (0-3 según probabilidad)
     */
    public function calculateNumberInvention(int $suerte_user, int $time): int
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
     * Guarda los materiales en el inventario del jugador directamente como disponibles.
     * 
     * Si el jugador ya tiene el material, se suma a quantity.
     * Si no, se crea un nuevo registro con quantity.
     * 
     * @param array $resource Array con información del material obtenido ['type' => 'Material', 'id' => int, 'quantity' => int]
     * @return Material Material encontrado para mostrar en el mensaje
     */
    public function saveMaterials(array $resource): Material
    {
        $inventory = $this->userService->getUserInventory();

        $inventoryMaterial = InventoryMaterial::where('inventory_id', $inventory->id)
                                              ->where('material_id', $resource['id'])
                                              ->first();

        if ($inventoryMaterial) {
            InventoryMaterial::where('inventory_id', $inventory->id)
                ->where('material_id', $resource['id'])
                ->update(['quantity' => DB::raw('quantity + ' . (int) $resource['quantity'])]);
        } else {
            InventoryMaterial::create([
                'inventory_id' => $inventory->id,
                'material_id' => $resource['id'],
                'quantity' => (int) $resource['quantity'],
            ]);
        }

        return Material::findOrFail($resource['id']);
    }

    /**
     * Calcula, guarda y prepara los recursos para mostrarlos al usuario.
     * 
     * Cuando finaliza la acción de recolección:
     * - Calcula los recursos basándose en el event_id guardado en ActionZone
     * - Guarda los materiales directamente en quantity (disponibles)
     * - Crea los inventos con available=true
     * - Crea registros en resources con available=true
     * 
     * @param Action $action Acción de recolección completada
     * @return array|string Array de recursos encontrados o mensaje de error si no se encontró nada
     */
    public function updateResources(Action $action): array
    {
        return DB::transaction(function () use ($action) {
            $action_zone = $this->actionService->getActionZone($action);
            
            if (!$action_zone) {
                return ["No se encontró información de la acción de recolección."];
            }

            $zone_id = $action_zone->zone_id;
            $zone = $this->zoneService->getZoneWithRelations((string) $zone_id);
            
            // Calcular el multiplier basándose en el evento guardado
            $multiplier = 1.0;
            if ($action_zone->event_id) {
                $event = Event::find($action_zone->event_id);
                if ($event) {
                    $loss_percent = $event->loss_percent ?? 0;
                    $multiplier = (100 - $loss_percent) / 100;
                }
            }

            // Si multiplier = 0, no se encuentran recursos
            if ($multiplier === 0) {
                return ["Ohhh los eventos de esta zona no te han permitido encontrar nada en esta exploración. ¡Inténtalo de nuevo! Seguro que el evento ocurrido ha terminado."];
            }

            // Calcular tiempo de la acción en segundos (diferencia entre created_at y time, que es cuando finaliza)
            // Nota: El tiempo siempre será el mismo porque la acción se crea con tiempo fijo (5 segundos para pruebas)
            $time = $action->created_at->diffInSeconds($action->time);
            $suerte_user = $this->userService->getUserStat('Suerte');

            // Calcular recursos
            $farm_results = $this->calculateResources($zone, $suerte_user, $time, $multiplier);

            if (count($farm_results) === 0) {
                return ["No has encontrado recursos en esta exploración."];
            }

            // Guardar recursos directamente como disponibles
            $farm_result = $this->saveResourcesAndReturn($farm_results, $action_zone);

            return $farm_result;
        });
    }

    /**
     * Guarda los recursos directamente como disponibles y retorna el array formateado para el mensaje.
     * 
     * Los materiales se guardan en quantity (disponibles).
     * Los inventos se crean con available=true.
     * Se crean registros en resources con available=true.
     * 
     * @param array $results Array de recursos encontrados
     * @param ActionZone $action_zone Registro ActionZone asociado a la acción
     * @return array Array formateado con los recursos para mostrar en el mensaje flash
     */
    private function saveResourcesAndReturn(array $results, ActionZone $action_zone): array
    {
        $farm_result = [];

        foreach ($results as $resource) {
            if ($resource['type'] === 'Material') {
                $material = $this->saveMaterials($resource);
                $materialTypeName = $material->materialType->name ?? 'Desconocido';
                $farm_result[] = [
                    'name' => $material->name,
                    'type' => $materialTypeName,
                    'quantity' => $resource['quantity']
                ];

                Resource::create([
                    'action_zone_id' => $action_zone->id,
                    'resourceable_id' => $resource['id'],
                    'resourceable_type' => 'App\Models\Material',
                    'quantity' => $resource['quantity'],
                    'available' => true,
                ]);
            } elseif ($resource['type'] === 'Invention') {
                $invention = $this->inventionService->createInvention(
                    (string) $resource['invention_type_id'],
                    (string) $resource['material_id'],
                    0
                );
                $invention->update(['available' => true]);
                
                $farm_result[] = [
                    'name' => $invention->name,
                    'type' => 'Invento',
                    'quantity' => 1
                ];

                Resource::create([
                    'action_zone_id' => $action_zone->id,
                    'resourceable_id' => $invention->id,
                    'resourceable_type' => 'App\Models\Invention',
                    'quantity' => 1,
                    'available' => true,
                ]);
            }
        }

        return $farm_result;
    }

    /**
     * Obtiene los recursos no disponibles, que son los últimos recogidos
     * 
     * @param ActionZone $action_zone Registro ActionZone asociado a la acción
     * @return \Illuminate\Database\Eloquent\Collection Colección de recursos no disponibles
     */
    public function recolectResourcesNoAvailables(ActionZone $action_zone): Collection
    {
            $results = Resource::where('action_zone_id', $action_zone->id)
                        ->where('available', false)->get();
  
            return $results;
    }

    /**
     * Marca todos los recursos de una acción como disponibles
     * 
     * @param ActionZone $action_zone Registro ActionZone asociado a la acción
     * @return void
     */
    public function updateResourcesNoAvailables(ActionZone $action_zone): void
    {
        Resource::where('action_zone_id', $action_zone->id)
                    ->where('available', false)->update(['available' => true]);
    }

    /**
     * Obtiene los inventos del inventario del jugador agrupados por tipo
     * 
     * @return \Illuminate\Support\Collection Colección de inventos agrupados por invention_type_id
     */
    /**
     * Obtiene los inventos del inventario del jugador agrupados por tipo (solo disponibles)
     * 
     * @return \Illuminate\Support\Collection Colección de inventos agrupados por invention_type_id
     */
    public function getUserInventionsByType(): Collection
    {
        $inventory_user = $this->userService->getUserInventoryWithRelations();

        // Filtrar solo los inventos disponibles y agrupar por tipo
        $user_inventions_by_type = $inventory_user->inventions
            ->where('available', true)
            ->groupBy('invention_type_id');

        return $user_inventions_by_type;
    }

    /**
     * Obtiene los inventos del inventario del jugador agrupados por tipo sin precargar relaciones
     * 
     * @return \Illuminate\Support\Collection Colección de inventos agrupados por invention_type_id
     */
    public function getUserInventionsByTypeWithoutRelations(): Collection
    {
        $inventory_user = $this->userService->getUserInventory();

        $user_inventions_by_type = Invention::where('inventory_id', $inventory_user->id)->get()->groupBy('invention_type_id');
        return $user_inventions_by_type;
    }

    /**
     * Comprueba que el jugador posee los inventos necesarios para construir un edificio
     * 
     * @param \Illuminate\Database\Eloquent\Collection $invention_types_needed Tipos de inventos requeridos
     * @param int $num_needed Número de inventos necesarios de cada tipo
     * @param \Illuminate\Support\Collection $user_inventions_by_type Inventos del usuario agrupados por tipo
     * @return bool|\Illuminate\Http\RedirectResponse True si tiene suficientes inventos, RedirectResponse con error si no
     */
    public function checkInventionsToConstruct($invention_types_needed, int $num_needed, Collection $user_inventions_by_type): bool|RedirectResponse
    {
        foreach ($invention_types_needed as $type) {
            // Convertir el ID a string para comparar con el key del groupBy
            $type_id = (string) $type->id;
            
            // Verificar si el usuario tiene inventos de este tipo
            if (!isset($user_inventions_by_type[$type_id])) {
                return redirect()->back()
                    ->with('error', "No tienes inventos de tipo {$type->name}. Se requieren {$num_needed}.");
            }
            
            // Filtrar solo los inventos disponibles
            $available_inventions = $user_inventions_by_type[$type_id]->where('available', true);
            
            if($available_inventions->count() < $num_needed){
                return redirect()->back()
                    ->with('error', "No tienes suficientes inventos de tipo {$type->name}. Tienes {$available_inventions->count()} disponible(s) pero se requieren {$num_needed}.");
            }
        }
        return true;
    }

    /**
     * Comprueba que el jugador posee los inventos necesarios para crear un invento
     * 
     * @param \Illuminate\Database\Eloquent\Collection $invention_types_needed Tipos de inventos requeridos con sus cantidades
     * @param \Illuminate\Support\Collection $user_inventions_by_type Inventos del usuario agrupados por tipo
     * @return bool|\Illuminate\Http\RedirectResponse True si tiene suficientes inventos, RedirectResponse con error si no
     */
    public function checkInventionsToCreate($invention_types_needed, Collection $user_inventions_by_type): bool|RedirectResponse
    {
        foreach($invention_types_needed as $needed_type){
            // Convertir el ID a string para comparar con el key del groupBy
            $type_id = (string) $needed_type->invention_type_need_id;
            
            // Verificar si el usuario tiene inventos de este tipo
            if (!isset($user_inventions_by_type[$type_id])) {
                return redirect()->back()
                    ->with('error', "No tienes inventos de tipo {$needed_type->inventionTypeNeed->name}. Se requieren {$needed_type->quantity}.");
            }
            
            // Filtrar solo los inventos disponibles
            $available_inventions = $user_inventions_by_type[$type_id]->where('available', true);
            
            if($available_inventions->count() < $needed_type->quantity){
                return redirect()->back()
                    ->with('error', "No tienes suficientes inventos de tipo {$needed_type->inventionTypeNeed->name}. Tienes {$available_inventions->count()} pero se requieren {$needed_type->quantity}.");
            }
        }

        return true;
    }

    /**
     * Obtiene los materiales del inventario del jugador filtrados por tipo
     * 
     * @param string $material_type_id ID del tipo de material
     * @return \Illuminate\Support\Collection Colección de materiales del tipo especificado
     */
    public function getUserMaterialsByType(string $material_type_id): Collection
    {
        $inventory_user = $this->userService->getUserInventoryWithRelations();
        
        if (!$inventory_user) {
            return collect();
        }
        
        $materials_user = $inventory_user->inventoryMaterials->filter(function ($inventoryMaterial) use ($material_type_id) {
            return $inventoryMaterial->material && 
                   $inventoryMaterial->material->materialType && 
                   (string) $inventoryMaterial->material->materialType->id === (string) $material_type_id;
        });
        
        return $materials_user;
    }

    /**
     * Comprueba que el jugador posee los materiales necesarios para crear un invento
     * 
     * @param \Illuminate\Support\Collection $user_materials Materiales del usuario del tipo requerido
     * @param string $name Nombre del tipo de material
     * @return bool|\Illuminate\Http\RedirectResponse True si tiene materiales, RedirectResponse con error si no
     */
    public function checkMaterials(Collection $user_materials, string $name): bool|RedirectResponse
    {
        if (count($user_materials) <= 0) {
            return redirect()->back()->with('error', "No tienes materiales de tipo {$name}");
        }

        return true;
    }


    /**
     * Reduce la cantidad que hay en el inventario del material utilizado en la creación de inventos
     * Si la cantidad es 1, elimina el registro del inventario
     * 
     * @param string $material_id ID del material a decrementar
     * @return bool True si se decrementó o eliminó correctamente
     */
    public function decrementMaterial(string $material_id): bool
    {

        $inventory = $this->userService->getUserInventory();
        $material = InventoryMaterial::where('inventory_id', $inventory->id)
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
     * Los eventos ocurren con una probabilidad de 1/3 y reducen los recursos según su loss_percent
     * 
     * @param string $zone_id ID de la zona a explorar
     * @return array Array con 'multiplier' (float 0-1) y 'event' (Event|null)
     */
    public function generateEvents(string $zone_id): array
    {
        $events = Event::where('zone_id', $zone_id)->get();

        if ($events->isEmpty()) {
            return ['multiplier' => 1.0, 'event' => null];
        }

        $event_probability = rand(0, 2);
        /* Ocurren eventos 1 de cada 3 veces */
        if ($event_probability == 2) {
            $event = $events->random();
            $loss_percent = $event->loss_percent ?? 0;
            $multiplier = (100 - $loss_percent) / 100;
            
            return ['multiplier' => $multiplier, 'event' => $event];
        } else {
            return ['multiplier' => 1.0, 'event' => null];
        }
    }

    /**
     * Finaliza una acción de recolección: actualiza los recursos y los marca como disponibles
     * Si ocurrió un evento, guarda la información en la sesión para mostrarlo al usuario
     * 
     * @param Action $action Acción de recolección completada
     * @param string $userName Nombre del usuario
     * @return array Array con 'status' => 'completed' y 'results' => array de recursos
     */
    public function finishFarmAction(Action $action, string $userName): array
    {
        $action_zone = $this->actionService->getActionZone($action);
        
        // Si ocurrió un evento, mostrar información al usuario
        if ($action_zone && $action_zone->event_id) {
            $event = Event::find($action_zone->event_id);
            if ($event) {
                Session::flash('event_occurred', [
                    'name' => $event->name,
                    'description' => $event->description,
                    'loss_percent' => $event->loss_percent,
                ]);
            }
        }
        
        $results = $this->updateResources($action);

        // Verificar si el resultado es un mensaje de error o resultados válidos
        // Los mensajes de error son arrays con un solo string, los resultados válidos son arrays de arrays
        if (!empty($results) && count($results) > 0) {
            $firstResult = $results[0];
            // Si el primer elemento es un string, es un mensaje de error
            if (is_string($firstResult)) {
                // Es un mensaje de error, mostrarlo como error en lugar de como recolección
                Session::flash('error', $firstResult);
            } else {
                // Es un resultado válido (array con nombre => cantidad)
                Session::flash('success_message', 'Enhorabuena, has terminado de explorar y has recolectado:');
                Session::flash('data', $results);
            }
        }

        return ['status' => 'completed', 'results' => $results];
    }

    /**
     * Valida que los inventos seleccionados para construir un edificio sean válidos
     * 
     * @param array $inventions Array de inventos seleccionados agrupados por tipo [tipo_id => [invention_ids]]
     * @param Collection $invention_types_needed Tipos de inventos requeridos
     * @param int $building_level Nivel del edificio a construir
     * @return array ['valid' => bool, 'error' => string|null] Array con resultado de validación
     */
    public function validateSelectedInventionsForBuilding(array $inventions, Collection $invention_types_needed, int $building_level): array
    {
        $user_inventory = $this->userService->getUserInventory();
        $user_invention_ids = $user_inventory->inventions->where('available', true)->pluck('id')->toArray();

        foreach ($invention_types_needed as $needed_type) {
            $selected_inventions = $inventions[$needed_type->id] ?? [];
            
            // Validar cantidad
            if (count($selected_inventions) !== $building_level) {
                return [
                    'valid' => false,
                    'error' => "Debes seleccionar exactamente {$building_level} invento(s) del tipo {$needed_type->name}.",
                ];
            }

            // Validar que los inventos seleccionados pertenezcan al usuario, estén disponibles y sean del tipo correcto
            foreach ($selected_inventions as $invention_id) {
                $invention = Invention::find($invention_id);
                
                if (!$invention) {
                    return [
                        'valid' => false,
                        'error' => 'Uno de los inventos seleccionados no existe.',
                    ];
                }

                if (!in_array($invention_id, $user_invention_ids)) {
                    return [
                        'valid' => false,
                        'error' => 'Uno de los inventos seleccionados no pertenece a tu inventario o no está disponible.',
                    ];
                }

                if ($invention->invention_type_id != $needed_type->id) {
                    return [
                        'valid' => false,
                        'error' => 'Uno de los inventos seleccionados no es del tipo correcto.',
                    ];
                }
            }
        }

        return ['valid' => true, 'error' => null];
    }

    /**
     * Valida que los inventos seleccionados para crear un invento sean válidos
     * 
     * @param array $inventions Array de inventos seleccionados agrupados por tipo [tipo_id => [invention_ids]]
     * @param array $invention_types_needed Tipos de inventos requeridos con sus cantidades
     * @return array ['valid' => bool, 'error' => string|null] Array con resultado de validación
     */
    public function validateSelectedInventionsForInvention(array $inventions, Collection $invention_types_needed): array
    {
        $user_inventory = $this->userService->getUserInventory();
        $user_invention_ids = $user_inventory->inventions->where('available', true)->pluck('id')->toArray();

        foreach ($invention_types_needed as $needed) {
            $selected_inventions = $inventions[$needed->invention_type_need_id] ?? [];
            
            // Validar cantidad
            if (count($selected_inventions) !== $needed->quantity) {
                return [
                    'valid' => false,
                    'error' => "Debes seleccionar exactamente {$needed->quantity} invento(s) del tipo {$needed->inventionTypeNeed->name}.",
                ];
            }

            // Validar que los inventos seleccionados pertenezcan al usuario, estén disponibles y sean del tipo correcto
            foreach ($selected_inventions as $invention_id) {
                $invention = Invention::find($invention_id);
                
                if (!$invention) {
                    return [
                        'valid' => false,
                        'error' => 'Uno de los inventos seleccionados no existe.',
                    ];
                }

                if (!in_array($invention_id, $user_invention_ids)) {
                    return [
                        'valid' => false,
                        'error' => 'Uno de los inventos seleccionados no pertenece a tu inventario o no está disponible.',
                    ];
                }

                if ($invention->invention_type_id != $needed->invention_type_need_id) {
                    return [
                        'valid' => false,
                        'error' => 'Uno de los inventos seleccionados no es del tipo correcto.',
                    ];
                }
            }
        }

        return ['valid' => true, 'error' => null];
    }

}
