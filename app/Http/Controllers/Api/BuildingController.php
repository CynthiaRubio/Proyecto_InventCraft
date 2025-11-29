<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Building;
use App\Contracts\BuildingServiceInterface;

class BuildingController extends Controller
{
    /**
     * Constructor del controlador.
     * 
     * @param BuildingServiceInterface $buildingService Servicio de edificios
     */
    public function __construct(
        private BuildingServiceInterface $buildingService,
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
        return response()->json(['buildings' => $buildings], 200);
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

        return response()->json([
            'building' => $building,
            'actual_level' => $actual_level,
            'efficiency' => $efficiency,
            'can_build_space_station' => $canBuildSpaceStation,
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

