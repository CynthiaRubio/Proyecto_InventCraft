<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\InventionServiceInterface;
use App\Contracts\UserServiceInterface;
use App\Contracts\ActionServiceInterface;
use App\Models\Action;
use App\Models\Stat;
use App\Models\UserStat;
use App\Models\User;
use App\Models\Zone;
use App\Models\Invention;
use App\Models\Material;
use App\Models\ActionType;
use App\Models\Inventory;
use App\Models\InventoryMaterial;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class InventionService implements InventionServiceInterface
{
    public function __construct(
        private UserServiceInterface $userService,
        private ActionServiceInterface $actionService,
    ) {
    }

    /**
     * Crea un invento nuevo en el inventario del usuario autenticado
     * El invento se crea como no disponible hasta que se complete la acción
     * 
     * @param string $invention_type_id ID del tipo de invento
     * @param string $material_id ID del material con el que se crea el invento
     * @param int $time Tiempo dedicado a la creación (afecta la eficiencia)
     * @return Invention Invento creado
     */
    public function createInvention(string $invention_type_id, string $material_id, int $time): Invention
    {
        $faker = Faker::create();

        /* Recuperamos el inventario del usuario */
        $inventory = $this->userService->getUserInventory();

        /* Calculamos la eficiencia del invento llamando a la función encargada de ello */
        $efficiency = $this->efficiencyInvention($material_id, $time);

        /* Obtenemos el tipo de invento para generar un nombre apropiado */
        $inventionType = \App\Models\InventionType::findOrFail($invention_type_id);
        $inventionName = $inventionType->name . ' ' . $faker->unique()->word();

        /* Creamos el invento */
        $new_invention = Invention::create([
            'invention_type_id' => $invention_type_id,
            'material_id' => $material_id,
            'inventory_id' => $inventory->id,
            'name' => $inventionName,
            'efficiency' => $efficiency,
            'available' => false,
        ]);

        return $new_invention;
    }

    /**
     * Calcula la eficiencia del nuevo invento creado
     * Fórmula: eficiencia_material + (ingenio_usuario / 10) + (tiempo / 30)
     * Máximo: 100
     * 
     * @param string $material_id ID del material usado
     * @param int $time Tiempo dedicado a la creación
     * @return float Eficiencia calculada (0-100)
     */
    public function efficiencyInvention(string $material_id, int $time): float
    {

        /* Obtenemos los datos del material con el id que nos pasan */
        $material = Material::findOrFail($material_id);

        /* Calculamos la eficiencia del invento */
        $efficiency = $material->efficiency + 
                ($this->userService->getUserStat('Ingenio') / 10) +
                ($time / 30);

        return min($efficiency, 100);
    }

    /**
     * Elimina los inventos que han sido usados en la creación de otro invento o construcción
     * 
     * @param array $inventions Array de inventos usados agrupados por tipo [tipo_id => [invention_ids]]
     * @param string $newInventionId ID del invento creado o del ActionBuilding
     * @param string $model Tipo de modelo: 'Invention' o 'Building'
     * @return void
     */
    public function eliminateInventionsUsed(array $inventions, string $newInventionId, string $model): void
    {
        foreach ($inventions as $inventionTypeId => $selectedInventions) {
            if(!empty($selectedInventions)){
                foreach ($selectedInventions as $invention_id) {
                    $invention_to_delete = Invention::find($invention_id);
                    if($invention_to_delete){
                        $invention_to_delete->delete();
                    }
                }
            }
        }
    }

    /**
     * Crea un invento completo: crea el invento, crea la acción, decrementa materiales y elimina inventos usados
     * 
     * @param string $invention_type_id ID del tipo de invento
     * @param string $material_id ID del material a usar
     * @param int $time Tiempo dedicado a la creación
     * @param array|null $inventions_used Array de inventos usados (opcional)
     * @return array ['invention' => Invention, 'action' => Action]
     */
    public function createInventionWithAction(string $invention_type_id, string $material_id, int $time, ?array $inventions_used = null): array
    {
        return DB::transaction(function () use ($invention_type_id, $material_id, $time, $inventions_used) {
            // El ingenio reduce el tiempo de creación de inventos
            $ingenio_user = $this->userService->getUserStat('Ingenio');
            $timeReduced = max(1, $time - $ingenio_user); // Mínimo 1 minuto
            
            // Crea el invento (usa el tiempo original para calcular eficiencia)
            $new_invention = $this->createInvention($invention_type_id, $material_id, $time);

            // Crea la acción de crear invento (usa el tiempo reducido por ingenio)
            $action = $this->actionService->createAction('Crear', (string) $new_invention->id, 'Invention', $timeReduced);

            // Decrementa el material usado
            $this->decrementMaterial($material_id);

            // Si hay inventos usados, los elimina
            if ($inventions_used !== null && !empty($inventions_used)) {
                $this->eliminateInventionsUsed($inventions_used, (string) $new_invention->id, 'Invention');
            }

            return [
                'invention' => $new_invention,
                'action' => $action,
            ];
        });
    }

    /**
     * Reduce la cantidad que hay en el inventario del material utilizado en la creación de inventos
     * 
     * @param string $material_id ID del material a decrementar
     * @return bool
     */
    private function decrementMaterial(string $material_id)
    {
        $inventory = $this->userService->getUserInventory();
        $material = InventoryMaterial::where('inventory_id', $inventory->id)
            ->where('material_id', $material_id)
            ->first();

        if ($material) {
            if ($material->quantity > 1) {
                InventoryMaterial::where('inventory_id', $inventory->id)
                    ->where('material_id', $material_id)
                    ->update(['quantity' => DB::raw('quantity - 1')]);
            } else {
                InventoryMaterial::where('inventory_id', $inventory->id)
                    ->where('material_id', $material_id)
                    ->delete();
            }
            return true;
        }

        return false;
    }

    /**
     * Finaliza una acción de creación de invento: marca el invento como disponible
     * 
     * @param Action $action Acción de creación completada
     * @param string $userName Nombre del usuario
     * @return array Array con 'status' => 'completed'
     */
    public function finishCreationAction(Action $action, string $userName): array
    {
        $user = $this->userService->getUser();
        $inventory = $this->userService->getUserInventory();

        if ($inventory) {
            Invention::where('inventory_id', $inventory->id)
                ->where('available', false)
                ->where('id', $action->actionable_id)
                ->update(['available' => true]);
        }

        Session::flash('success', "$userName, has terminado la creación de tu invento gracias a tu ingenio.");

        return ['status' => 'completed'];
    }

}
