<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Building;
use App\Contracts\BuildingServiceInterface;
use App\Contracts\ActionServiceInterface;
use App\ViewModels\BuildingShowViewModel;

class BuildingController extends Controller
{
    /**
     * Constructor del controlador.
     * 
     * @param BuildingServiceInterface $buildingService Servicio de edificios
     */
    public function __construct(
        private BuildingServiceInterface $buildingService,
        private ActionServiceInterface $actionService,
    ) {
    }

    /**
     * Muestra una lista de todos los edificios.
     * 
     * @return \Illuminate\View\View Vista con la lista de edificios
     */
    public function index()
    {
        $buildings = Building::all();
        
        $canBuildSpaceStation = $this->buildingService->canBuildSpaceStation();
        
        return view('buildings.index', [
            'buildings' => $buildings,
            'canBuildSpaceStation' => $canBuildSpaceStation,
        ]);
    }

    /**
     * Muestra los detalles de un edificio específico.
     * 
     * @param string $id ID del edificio
     * @return \Illuminate\View\View Vista con los detalles del edificio
     */
    public function show(string $id)
    {
        $building = $this->buildingService->getBuildingWithRelations($id);
        $actual_level = $this->buildingService->getActualLevel($id);
        $efficiency = $this->buildingService->getEfficiency($id);

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

        $viewModel = new BuildingShowViewModel(
            building: $building,
            actualLevel: $actual_level,
            efficiency: $efficiency,
            canBuildSpaceStation: $canBuildSpaceStation,
            isUnderConstruction: $isUnderConstruction,
        );

        return view('buildings.show', compact('viewModel', 'building'));
    }

    /**
     * Muestra la pantalla de victoria cuando el usuario ha construido la Estación Espacial.
     * 
     * @return \Illuminate\View\View Vista de victoria
     */
    public function victory()
    {
        $user = auth()->user();
        return view('buildings.victory', compact('user'));
    }

}
