<?php

namespace App\Services;

use App\Models\Action;
use App\Models\Stat;
use App\Models\UserStat;
use App\Models\User;
use App\Models\Zone;
use App\Models\ActionType;
use App\Models\Inventory;

class UserManagementService {

    /**
     * Función que crea un usuario nuevo con los datos que le llegan
     */
    public function createUser($userData)
    {
        return User::create($userData);
    }

    /**
     * Función para actualizar un campo del usuario
     */
    public function updateUser($userId, $userData)
    {
        $user = User::findOrFail($userId);
        $user->update($userData);

        return $user;
    }

    /**
     * Función para borrar un usuario a traves de su id
     */
    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);
        $user->delete();

        return $user;
    }

    /**
     * Función para encontrar un usuario en la BD por su id
     */
    public function getUser($userId)
    {
        return User::findOrFail($userId);
    }

    /**
     * Obtiene el valor de una estadística de usuario
     */
    public function getUserStat(string $name)
    {
        $stat_id = Stat::where('name', $name)->first()->id;
        $user_id = auth()->user()->id;
        $value_stat = UserStat::where('user_id', $user_id)
                        ->where('stat_id', $stat_id)
                        ->value('value') ?? 0; // Valor por defecto si no tiene la estadística
        
        return $value_stat; 
    }

    /**
     * Obtiene la última zona en la que estuvo el usuario
     */
    public function getUserActualZone()
    {
        $action_type_id = ActionType::where('name', 'Mover')->first()->id;
        $user_id = auth()->user()->id;
        $user_action_zone_id = Action::where('user_id', $user_id)
                                    ->where('action_type_id', $action_type_id)
                                    ->latest('id')
                                    ->value('actionable_id');
        
        return $user_action_zone_id ? Zone::find($user_action_zone_id) : null;
    }

    /**
     * Función para obtener el id del inventario del jugador
     */
    public function getUserInventory(){
        $user = auth()->user();
        $inventory = Inventory::where('user_id', $user->id)->first();
        return $inventory;
    }

    /**
     * Función para obtener el id del inventario del jugador con sus relaciones
     */
    public function getUserInventoryWithRelations(){
        $user = auth()->user();
        $inventory_with_relations = Inventory::where('user_id', $user->id)
                        ->with('inventions' , 'materials');
        return $inventory_with_relations;
    }

}