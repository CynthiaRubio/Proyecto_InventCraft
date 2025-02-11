<?php

namespace App\Services;

use App\Models\Action;
use App\Models\ActionBuilding;
use App\Models\Building;
use App\Models\BuildingStat;
use App\Models\Stat;
use App\Models\Invention;


class BuildingManagementService
{
    public function __construct(
        private ActionManagementService $action_service,
        private UserManagementService $user_service,
    ) {
    }

    /**
     * Obtiene un edificio por ID
     */
    public function getBuilding(string $building_id)
    {
        $building = Building::findOrFail($building_id);
        return $building;
    }

    /**
     * Obtiene un edificio con relaciones precargadas
     */
    public function getBuildingWithRelations(string $building_id)
    {
        $building = Building::with(['actions','inventionTypes','stats'])->findOrFail($building_id);

        return $building;
    }

    /**
     * Obtiene el nivel actual de un edificio
     */
    public function getActualLevel(string $building_id)
    {
        $user = $this->user_service->getUser();

        $actual_level = Action::where('user_id', $user->_id)
                            ->where('actionable_id', $building_id)
                            ->where('finished' , true)
                            ->count();

        return $actual_level;
    }

    /**
     * Obtiene la eficiencia actual de un edificio
     */
    public function getEfficiency(string $building_id){

        $action_id = $this->action_service->getLastActionConstruct($building_id);

        if($action_id){
            $action_building = ActionBuilding::where('action_id', $action_id)->where('building_id', $building_id)->first();
            return round($action_building->efficiency , 2);
        } else {
            return 0;
        }   
    }


    /**
     * Calcula el tiempo de construcción de un edificio
     */
    public function getConstructTime($building_level){

        $user = $this->user_service->getUser();

        if($user->level === 0){
            $user->level = 1;
        }

        $constructTime = (600 / $user->level) * $building_level; 

        return $constructTime;
    }


    /**
     * Calcula la eficiencia del edificio construido a partir de los inventos usados
     */
    public function calculateEfficiencyBuilding ($building_id, $building_level, $inventions_used){

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

        if($num_inventions !== 0){
            $efficiency = ($efficiency / $num_inventions) / ($building_level * 2);
        }

        if($building_level > 1){
            $actual_efficiency = $this->getEfficiency($building_id);
            $efficiency += $actual_efficiency;
        }

        return round($efficiency , 2);
    }
    
}
