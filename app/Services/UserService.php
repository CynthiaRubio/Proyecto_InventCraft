<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\UserServiceInterface;
use App\Models\Action;
use App\Models\Stat;
use App\Models\UserStat;
use App\Models\User;
use App\Models\Zone;
use App\Models\ActionType;
use App\Models\Inventory;
use App\Models\InventoryMaterial;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class UserService implements UserServiceInterface
{
    /**
     * Obtiene el usuario autenticado actual
     * 
     * @return User|null Usuario autenticado o null si no hay sesión
     */
    public function getUser(): ?User
    {
        return auth()->user();
    }

    /**
     * Obtiene el valor de una estadística del usuario autenticado
     * 
     * @param string $name Nombre de la estadística ('Suerte', 'Vitalidad', 'Ingenio', 'Velocidad')
     * @return int Valor de la estadística (0 si no existe)
     */
    public function getUserStat(string $name): int
    {
        $stat_id = Stat::where('name', $name)->first()->id;
        $user_id = auth()->user()->id;
        $value_stat = UserStat::where('user_id', $user_id)
                        ->where('stat_id', $stat_id)
                        ->value('value') ?? 0; // Valor por defecto si no tiene la estadística

        return (int) $value_stat;
    }

    /**
     * Obtiene la última zona en la que estuvo el usuario (última acción de movimiento completada)
     * 
     * @return Zone|null Última zona visitada o null si no ha movido nunca
     */
    public function getUserActualZone(): ?Zone
    {
        $action_type_id = ActionType::where('name', 'Mover')->first()->id;
        $user_id = auth()->user()->id;
        $user_action_zone_id = Action::where('user_id', $user_id)
                                    ->where('action_type_id', $action_type_id)
                                    ->where('finished' , true)
                                    ->latest('id')
                                    ->value('actionable_id');

        return $user_action_zone_id ? Zone::find((string) $user_action_zone_id) : null;
    }

    /**
     * Obtiene el inventario del jugador autenticado
     * 
     * @return Inventory|null Inventario del usuario o null si no existe
     */
    public function getUserInventory(): ?Inventory
    {
        $user = auth()->user();

        $inventory = Inventory::where('user_id', $user->id)->first();
        return $inventory;
    }

    /**
     * Obtiene el inventario del jugador con sus relaciones precargadas (materials, inventions)
     * 
     * @return Inventory|null Inventario con relaciones o null si no existe
     */
    public function getUserInventoryWithRelations(): ?Inventory
    {
        $user = auth()->user();

        $inventory = Inventory::where('user_id', $user->id)
                        ->with(['inventoryMaterials.material.materialType', 'inventions.inventionType'])
                        ->first();

        return $inventory;
    }

    /**
     * Obtiene un usuario por su ID
     * 
     * @param int $userId ID del usuario
     * @return User Usuario encontrado
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si no se encuentra el usuario
     */
    public function getUserById(int $userId): User
    {
        return User::findOrFail($userId);
    }


    /**
     * Crea un usuario nuevo con los datos proporcionados
     * 
     * @param array $userData Datos del usuario (name, email, password, etc.)
     * @return User Usuario creado
     */
    public function createUser(array $userData): User
    {
        return User::create($userData);
    }

    /**
     * Actualiza los datos de un usuario
     * 
     * @param int $userId ID del usuario
     * @param array $userData Datos a actualizar
     * @return bool True si se actualizó correctamente
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si no se encuentra el usuario
     */
    public function updateUser(int $userId, array $userData): bool
    {
        $user = User::findOrFail($userId);
        return $user->update($userData);
    }

    /**
     * Elimina un usuario de la base de datos
     * 
     * @param int $userId ID del usuario
     * @return bool True si se eliminó correctamente
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si no se encuentra el usuario
     */
    public function deleteUser(int $userId): bool
    {
        $user = User::findOrFail($userId);
        return $user->delete();
    }

    /**
     * Registra un nuevo usuario completo: crea usuario, inventario, acción inicial y stats
     * 
     * @param array $userData Datos del usuario (name, email, password)
     * @param Zone $initialZone Zona inicial aleatoria
     * @return User Usuario creado
     */
    public function registerUser(array $userData, Zone $initialZone): User
    {
        return DB::transaction(function () use ($userData, $initialZone) {
            // Crear el usuario
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
                'level' => 0,
                'experience' => 0,
                'unasigned_points' => 15,
                'avatar' => 0,
            ]);

            // Crear el inventario del usuario
            Inventory::create([
                'user_id' => $user->id,
            ]);

            // Crear la primera acción de mover para situarlo en la zona aleatoria
            $action_type_id = ActionType::where('name', 'Mover')->first()->id;
            $actionable_type = "App\Models\Zone";

            Action::create([
                'user_id' => $user->id,
                'action_type_id' => $action_type_id,
                'actionable_id' => $initialZone->id,
                'actionable_type' => $actionable_type,
                'time' => now()->addSeconds(0),
                'finished' => true,
                'notification' => false,
                'updated' => true,
            ]);

            // Crear las estadísticas del usuario
            $stats = Stat::all();
            foreach ($stats as $stat) {
                UserStat::create([
                    'user_id' => $user->id,
                    'stat_id' => $stat->id,
                    'value' => 0,
                ]);
            }

            return $user;
        });
    }

    /**
     * Actualiza las estadísticas de un usuario asignando puntos
     * 
     * @param int $userId ID del usuario
     * @param array $stats Array de stats con sus valores incrementales [stat_id => value]
     * @return int Total de puntos asignados
     */
    public function updateUserStats(int $userId, array $stats): int
    {
        return DB::transaction(function () use ($userId, $stats) {
            $totalAssigned = array_sum($stats);

            foreach ($stats as $statId => $value) {
                $userStat = UserStat::where('user_id', $userId)
                    ->where('stat_id', $statId)
                    ->first();
                
                if ($userStat) {
                    $new_value = $userStat->value + (int) $value;
                    UserStat::where('user_id', $userId)
                        ->where('stat_id', $statId)
                        ->update(['value' => $new_value]);
                }
            }

            // Actualizar puntos no asignados
            $user = User::findOrFail($userId);
            $user->unasigned_points -= $totalAssigned;
            $user->save();

            return $totalAssigned;
        });
    }

    /**
     * Obtiene todos los usuarios ordenados por ranking (nivel descendente, luego experiencia descendente)
     * 
     * @return \Illuminate\Database\Eloquent\Collection Colección de usuarios ordenados
     */
    public function getRanking(): Collection
    {
        return User::orderByDesc('level')
            ->orderByDesc('experience')
            ->get();
    }

    /**
     * Obtiene todos los usuarios de la base de datos
     * 
     * @return \Illuminate\Database\Eloquent\Collection Colección de todos los usuarios
     */
    public function getAllUsers(): Collection
    {
        return User::all();
    }

    /**
     * Añade experiencia al usuario y devuelve la cantidad añadida
     * 
     * @param int $userId ID del usuario
     * @param int $experience Cantidad de experiencia a añadir
     * @return int Cantidad de experiencia añadida
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si no se encuentra el usuario
     */
    public function addExperience(int $userId, int $experience): int
    {
        $user = User::findOrFail($userId);
        $new_experience = $user->experience + $experience;
        $user->update(['experience' => $new_experience]);
        return $experience;
    }

    /**
     * Verifica y actualiza el nivel del usuario si tiene suficiente experiencia
     * La experiencia requerida por nivel es: (nivel_actual + 1) * 100
     * Al subir de nivel, se añaden 15 puntos no asignados
     * 
     * @param int $userId ID del usuario
     * @return bool True si subió de nivel, false en caso contrario
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si no se encuentra el usuario
     */
    public function checkAndUpdateLevel(int $userId): bool
    {
        $user = User::findOrFail($userId);
        $experience_by_level = ($user->level + 1) * 100;

        if ($user->experience >= $experience_by_level) {
            $new_experience = $user->experience - $experience_by_level;
            $new_level = $user->level + 1;
            $new_unasigned_points = $user->unasigned_points + 15;
            
            $user->update([
                'experience' => $new_experience,
                'level' => $new_level,
                'unasigned_points' => $new_unasigned_points,
            ]);
            
            return true;
        }

        return false;
    }

}
