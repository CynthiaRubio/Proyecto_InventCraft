<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\BuildingServiceInterface;
use App\Contracts\ActionServiceInterface;
use App\Contracts\UserServiceInterface;
use App\Contracts\InventionServiceInterface;
use App\Models\Action;
use App\Models\ActionBuilding;
use App\Models\Building;
use App\Models\BuildingStat;
use App\Models\Stat;
use App\Models\Invention;
use App\Models\InventionType;
use App\Models\UserStat;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;


class BuildingService implements BuildingServiceInterface
{
    public function __construct(
        private ActionServiceInterface $actionService,
        private UserServiceInterface $userService,
        private InventionServiceInterface $inventionService,
    ) {
    }

    /**
     * Obtiene un edificio por ID
     * 
     * @param string $building_id ID del edificio
     * @return Building Edificio encontrado
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si no se encuentra el edificio
     */
    public function getBuilding(string $building_id): Building
    {
        $building = Building::findOrFail($building_id);
        return $building;
    }

    /**
     * Obtiene un edificio con relaciones precargadas (actions, inventionTypes, stats)
     * 
     * @param string $building_id ID del edificio
     * @return Building Edificio con relaciones precargadas
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si no se encuentra el edificio
     */
    public function getBuildingWithRelations(string $building_id): Building
    {
        $building = Building::with(['actions','inventionTypes','buildingStats.stat'])->findOrFail($building_id);

        return $building;
    }

    /**
     * Obtiene el nivel actual de un edificio (número de construcciones completadas)
     * 
     * @param string $building_id ID del edificio
     * @return int Nivel actual del edificio (0 si no se ha construido nunca)
     */
    public function getActualLevel(string $building_id): int
    {
        $user = $this->userService->getUser();

        $actual_level = Action::where('user_id', $user->id)
                            ->where('actionable_id', $building_id)
                            ->where('finished' , true)
                            ->count();

        return $actual_level;
    }

    /**
     * Obtiene la eficiencia actual de un edificio basada en la última construcción
     * 
     * @param string $building_id ID del edificio
     * @return float Eficiencia del edificio (0-100) o 0 si no se ha construido nunca
     */
    public function getEfficiency(string $building_id): float
    {

        $action_id = $this->actionService->getLastActionConstruct($building_id);

        if($action_id){
            $action_building = ActionBuilding::where('action_id', $action_id)->where('building_id', $building_id)->first();
            if($action_building){
                return round($action_building->efficiency , 2);
            }
        } else {
            return 0;
        }   
    }


    /**
     * Calcula el tiempo de construcción de un edificio en minutos
     * Fórmula: (600 / (nivel_usuario + 1)) * nivel_edificio - vitalidad_usuario
     * 
     * @param int $building_level Nivel del edificio a construir
     * @return int Tiempo de construcción en minutos (redondeado, mínimo 1)
     */
    public function getConstructTime(int $building_level): int
    {
        $user = $this->userService->getUser();
        $vitalidad_user = $this->userService->getUserStat('Vitalidad');

        $constructTimeMinutes = (600 / ($user->level + 1) ) * $building_level;
        
        $constructTimeMinutes -= $vitalidad_user;

        return (int) max(1, round($constructTimeMinutes));
    }


    /**
     * Calcula la eficiencia del edificio construido a partir de los inventos usados
     * 
     * Fórmula: (suma_eficiencias_inventos / num_inventos) / max(2, 1 + building_level)
     * 
     * @param string $building_id ID del edificio
     * @param int $building_level Nivel del edificio a construir
     * @param array $inventions_used Array de inventos usados agrupados por tipo [tipo_id => [invention_ids]]
     * @return float Eficiencia calculada (máximo 100)
     */
    public function calculateEfficiencyBuilding(string $building_id, int $building_level, array $inventions_used): float
    {
        $efficiency = 0;
        $num_inventions = 0;

        foreach($inventions_used as $type => $array_invention){
            if(!empty($array_invention)){
                /* Recuperamos los detalles de los inventos usados */
                $inventions_details = Invention::whereIn( 'id' , $array_invention)->get();

                foreach($inventions_details as $invention){
                    if($invention->efficiency != null){
                        $efficiency += $invention->efficiency;
                        $num_inventions++;
                    }
                }
            }
        }

        $actual_efficiency = $this->getEfficiency($building_id);
        $efficiency_remaining = 100 - $actual_efficiency;

        if($num_inventions !== 0){
            
            $base_efficiency = ($efficiency / $num_inventions) / max(2, 1 + $building_level);
            
            if ($efficiency_remaining > 0 && $efficiency_remaining < 5) {
                $base_efficiency = max($base_efficiency, 0.5);
            } elseif ($efficiency_remaining > 0 && $efficiency_remaining < 10) {
                $base_efficiency = max($base_efficiency, 1.0);
            }
            
            $efficiency = $base_efficiency;
        }

        if($building_level > 1){
            $efficiency += $actual_efficiency;
        }

        return min($efficiency , 100);
    }


    /**
     * Actualiza las estadísticas del usuario tras construir un edificio
     * Suma los valores de las estadísticas asociadas al edificio a las del usuario
     * 
     * @param string $building_id ID del edificio construido
     * @return array Array con las estadísticas actualizadas ['name' => string, 'value' => int]
     */
    public function updateUserStats(string $building_id): void
    {

        $user = $this->userService->getUser();

        $building_stats = $this->getBuildingStats($building_id);

        foreach($building_stats as $stat){

            $details_stat = Stat::where('name', $stat['name'])->first();
            $user_stat = UserStat::where('user_id', $user->id)->where('stat_id', $details_stat->id)->first();
            
            if ($user_stat) {
                $new_value = $user_stat->value + $stat['value'];
                UserStat::where('user_id', $user->id)
                    ->where('stat_id', $details_stat->id)
                    ->update(['value' => $new_value]);
            }
        }
    }

    /**
     * Recupera las estadísticas asociadas al edificio
     * 
     * @param string $building_id ID del edificio
     * @return array Array de estadísticas ['name' => string, 'value' => int]
     */
    public function getBuildingStats(string $building_id): array
    {
        $building_stats = BuildingStat::where('building_id', $building_id)->get();

        $stats = [];

        foreach ($building_stats as $stat) {
            $stat_value = $stat->value;

            $stats[] = [
                'name' => $stat->stat->name,
                'value' => $stat_value,
            ];
        }

        return $stats;
    }

    /**
     * Obtiene los tipos de inventos necesarios para construir un edificio
     * 
     * @param string $building_id ID del edificio
     * @return \Illuminate\Database\Eloquent\Collection Colección de tipos de inventos requeridos
     */
    public function getInventionTypesNeededForBuilding(string $building_id): Collection
    {
        return InventionType::where('building_id', $building_id)->get();
    }

    /**
     * Construye un edificio: crea la acción, calcula eficiencia, crea ActionBuilding y elimina inventos usados
     * 
     * @param string $building_id ID del edificio a construir
     * @param int $building_level Nivel del edificio a construir
     * @param array $inventions_used Array de inventos usados agrupados por tipo
     * @param int|null $time Tiempo de construcción (opcional, si es null se calcula automáticamente)
     * @return array ['action' => Action, 'action_building' => ActionBuilding, 'construct_time' => int] o ['error' => string]
     */
    public function constructBuilding(string $building_id, int $building_level, array $inventions_used, ?int $time = null): array
    {
        $building = Building::findOrFail($building_id);

        // Si es la Estación Espacial, verificar que se cumplan los requisitos
        if ($building->name === 'Estación Espacial') {
            $canBuild = $this->canBuildSpaceStation();
            if (!$canBuild['can_build']) {
                return [
                    'error' => $canBuild['reason'],
                    'buildings_status' => $canBuild['buildings_status'] ?? [],
                ];
            }
        }

        // Verificar que el edificio no tenga ya eficiencia 100%
        $actual_efficiency = $this->getEfficiency($building_id);
        $actual_level = $this->getActualLevel($building_id);
        if ($actual_efficiency >= 100 && $actual_level > 0) {
            return [
                'error' => 'Este edificio ya tiene eficiencia máxima (100%). No se puede mejorar más.',
            ];
        }

        return DB::transaction(function () use ($building_id, $building_level, $inventions_used, $time) {

            if ($time === null) {
                $time = $this->getConstructTime($building_level);
            }
            
            $action = $this->actionService->createAction('Construir', $building_id, 'Building', $time);

            $building_efficiency = $this->calculateEfficiencyBuilding($building_id, $building_level, $inventions_used);

            $action_building = $this->actionService->createActionBuilding($action, $building_id, $building_efficiency);

            $this->inventionService->eliminateInventionsUsed($inventions_used, (string) $action_building->id, 'Building');

            return [
                'action' => $action,
                'action_building' => $action_building,
                'construct_time' => $time,
            ];
        });
    }

    /**
     * Finaliza una acción de construcción: marca ActionBuilding como disponible y actualiza stats
     * Si se construyó la Estación Espacial, aplica bonificación y marca victoria
     * 
     * @param Action $action Acción de construcción completada
     * @param string $userName Nombre del usuario
     * @return array Array con 'status' => 'completed', 'victory' => bool, 'message' => string (opcional)
     */
    public function finishConstructionAction(Action $action, string $userName): array
    {
        return DB::transaction(function () use ($action, $userName) {
            $building = Building::findOrFail($action->actionable_id);
            
            ActionBuilding::where('action_id', $action->id)
                ->update(['available' => true]);

            $this->updateUserStats((string) $action->actionable_id);

            if ($building->name === 'Estación Espacial') {
                $this->applySpaceStationBonus();
                
                return [
                    'status' => 'completed',
                    'victory' => true,
                    'message' => "$userName, ¡FELICIDADES! Has construido la Estación Espacial y ganado el juego. ¡Victoria total!"
                ];
            }

            Session::flash('success', "$userName, terminaste la construcción de tu edificio. ¡Tus habilidades han mejorado!");

            return ['status' => 'completed', 'victory' => false];
        });
    }

    /**
     * Obtiene el edificio Estación Espacial
     * 
     * @return Building|null Edificio Estación Espacial o null si no existe
     */
    public function getSpaceStation(): ?Building
    {
        return Building::where('name', 'Estación Espacial')->first();
    }

    /**
     * Verifica si el usuario puede construir la Estación Espacial
     * Requiere que todos los demás edificios estén construidos (nivel > 0) y tengan eficiencia 100%
     * No hay límite máximo de nivel, solo se requiere eficiencia 100%
     * 
     * @return array Array con 'can_build' => bool, 'reason' => string, 'buildings_status' => array
     */
    public function canBuildSpaceStation(): array
    {
        $user = $this->userService->getUser();
        $spaceStation = $this->getSpaceStation();

        if (!$spaceStation) {
            return ['can_build' => false, 'reason' => 'La Estación Espacial no existe en la base de datos.'];
        }

        $requiredBuildings = Building::where('id', '!=', $spaceStation->id)->get();
        
        $buildingsStatus = [];
        $allAt100 = true;

        foreach ($requiredBuildings as $building) {
            $actualLevel = $this->getActualLevel((string) $building->id);
            $efficiency = $this->getEfficiency((string) $building->id);
            
            $buildingsStatus[] = [
                'building' => $building->name,
                'level' => $actualLevel,
                'efficiency' => $efficiency,
                'required_level' => 1,
                'required_efficiency' => 100,
            ];

            if ($actualLevel === 0 || $efficiency < 100) {
                $allAt100 = false;
            }
        }

        return [
            'can_build' => $allAt100,
            'buildings_status' => $buildingsStatus,
            'reason' => $allAt100 
                ? 'Todos los edificios están construidos y tienen eficiencia 100%. Puedes construir la Estación Espacial.' 
                : 'Necesitas que todos los edificios estén construidos y tengan eficiencia 100% antes de construir la Estación Espacial.',
        ];
    }

    /**
     * Verifica si el usuario ha ganado (ha construido la Estación Espacial)
     * 
     * @return bool True si el usuario ha completado la construcción de la Estación Espacial
     */
    public function checkVictory(): bool
    {
        $user = $this->userService->getUser();
        $spaceStation = $this->getSpaceStation();

        if (!$spaceStation) {
            return false;
        }

        $hasSpaceStation = Action::where('user_id', $user->id)
            ->where('actionable_id', $spaceStation->id)
            ->where('action_type_id', $this->actionService->getActionTypeId('Construir'))
            ->where('finished', true)
            ->exists();

        return $hasSpaceStation;
    }

    /**
     * Aplica la bonificación de la Estación Espacial: +5 puntos a una estadística aleatoria
     * Se ejecuta automáticamente cuando se completa la construcción de la Estación Espacial
     * 
     * @return void
     */
    private function applySpaceStationBonus(): void
    {
        $user = $this->userService->getUser();
        $stats = Stat::all();
        
        if ($stats->isEmpty()) {
            return;
        }

        $randomStat = $stats->random();
        
        $userStat = UserStat::where('user_id', $user->id)
            ->where('stat_id', $randomStat->id)
            ->first();

        if ($userStat) {
            $newValue = $userStat->value + 5;
            $userStat->update(['value' => $newValue]);
        }
    }
    
}
