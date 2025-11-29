<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Building;
use App\Contracts\BuildingServiceInterface;
use App\Contracts\ActionServiceInterface;

class BuildingController extends Controller
{
    /**
     * Constructor del controlador.
     * 
     * @param BuildingServiceInterface $buildingService Servicio de edificios
     * @param ActionServiceInterface $actionService Servicio de acciones
     */
    public function __construct(
        private BuildingServiceInterface $buildingService,
        private ActionServiceInterface $actionService,
    ) {
    }

    /**
     * Devuelve todos los edificios en formato JSON.
     * 
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con todos los edificios
     */
    public function index()
    {
        $buildings = Building::all();
        $canBuildSpaceStation = $this->buildingService->canBuildSpaceStation();
        
        return response()->json([
            'buildings' => $buildings,
            'can_build_space_station' => $canBuildSpaceStation,
        ], 200);
    }

    /**
     * Devuelve un edificio específico con sus relaciones en formato JSON.
     * 
     * @param string $id ID del edificio
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el edificio, nivel, eficiencia y requisitos
     */
    public function show(string $id)
    {
        $building = $this->buildingService->getBuildingWithRelations($id);
        $actual_level = $this->buildingService->getActualLevel($id);
        $efficiency = $this->buildingService->getEfficiency($id);

        // Si es la Estación Espacial, verificar si se puede construir
        $canBuildSpaceStation = null;
        if ($building->name === 'Estación Espacial') {
            $canBuildSpaceStation = $this->buildingService->canBuildSpaceStation();
        }

        // Verificar si hay una construcción en curso para este edificio
        $isUnderConstruction = false;
        $user = auth()->user();
        if ($user) {
            $currentAction = $this->actionService->getCurrentAction($user->id);
            if ($currentAction) {
                $actionType = $this->actionService->getActionTypeById($currentAction->action_type_id);
                // Comparar el tipo de acción y el tipo de modelo
                if ($actionType->name === 'Construir' && 
                    $currentAction->actionable_type === Building::class) {
                    // Comparar IDs convirtiendo ambos a string para evitar problemas de tipo
                    $buildingId = (string) $building->id;
                    $actionableId = (string) $currentAction->actionable_id;
                    if ($actionableId === $buildingId) {
                        $isUnderConstruction = true;
                    }
                }
            }
        }

        return response()->json([
            'building' => $building,
            'actual_level' => $actual_level,
            'efficiency' => $efficiency,
            'can_build_space_station' => $canBuildSpaceStation,
            'is_under_construction' => $isUnderConstruction,
        ], 200);
    }

    /**
     * Devuelve información sobre la victoria del juego.
     * 
     * @return \Illuminate\Http\JsonResponse Respuesta JSON indicando si el usuario ha ganado
     */
    public function victory()
    {
        $user = auth()->user();
        $hasWon = $this->buildingService->checkVictory();

        return response()->json([
            'has_won' => $hasWon,
            'user' => $user,
        ], 200);
    }
}

