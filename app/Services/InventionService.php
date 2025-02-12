<?php

namespace App\Services;

use App\Models\Action;
use App\Models\Stat;
use App\Models\UserStat;
use App\Models\User;
use App\Models\Zone;
use App\Models\Invention;
use App\Models\Material;
use App\Models\ActionType;
use Faker\Factory as Faker;

class InventionService
{
    public function __construct(
        private UserManagementService $user_service,
    ) {
    }

    /**
     * Función para crear un invento
     *
     * @param $invention_type_id: El id del tipo de invento
     * @param $material_id: El id del material con el que se va a crear
     * @param $time: El tiempo dedicado a la creación del invento
     */
    public function createInvention(string $invention_type_id, string $material_id, int $time)
    {
        $faker = Faker::create();

        /* Recuperamos el inventario del usuario */
        $inventory = $this->user_service->getUserInventory();

        /* Calculamos la eficiencia del invento llamando a la función encargada de ello */
        $efficiency = $this->efficiencyInvention($material_id, $time);

        /* Creamos el invento */
        $new_invention = Invention::create([
            'invention_type_id' => $invention_type_id,
            'material_id' => $material_id,
            'inventory_id' => $inventory->_id,
            'action_building_id' => null,
            'invention_created_id' => null,
            'name' => $faker->name(),
            'efficiency' => $efficiency,
            'available' => false,
        ]);

        return $new_invention;
    }

    /**
     * Función que calcula la eficiencia del nuevo invento creado
     *
     * @param $material_id: El id del material
     * @param $time: El tiempo dedicado a la cración del invento
     *
     */
    public function efficiencyInvention(string $material_id, int $time)
    {

        /* Obtenemos los datos del material con el id que nos pasan */
        $material = Material::findOrFail($material_id);

        /* Calculamos la eficiencia del invento */
        $efficiency = $material->efficiency + 
                ($this->user_service->getUserStat('Ingenio') / 10) +
                ($time / 30);

        return min($efficiency, 100);
    }

    /**
     * Funcion que actualiza y elimina los inventos que han sido usados en la creación de otro invento
     *
     * @param $inventions: El array de inventos usados
     * @param $id: El id del invento que se ha creado o el id de ActionBuilding
     * @param $model: 'Invention' o 'Building'
     */
    public function eliminateInventionsUsed(array $inventions, string $id, string $model)
    {

        foreach ($inventions as $inventionTypeId => $selectedInventions) {
            if(!empty ($selectedInventions) ){
                foreach ($selectedInventions as $invention_id) {
             
                    if ($model === 'Invention') {
                        Invention::where('id', $invention_id)->update([
                            'invention_created_id' => $id,
                            'available' => false,
                        ]);
                    } elseif ($model === 'Building') {
                        Invention::where('id', $invention_id)->update([
                            'action_building_id' => $id,
                            'available' => false,
                        ]);
                    }
                    
                    $invention_to_delete = Invention::find($invention_id);
                    if($invention_to_delete){
                        $invention_to_delete->delete();
                    }
                }
            }
        }
    }

}
