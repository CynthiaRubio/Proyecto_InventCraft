<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Invention;
use App\Models\User;
use App\Contracts\UserServiceInterface;
use App\Contracts\ActionServiceInterface;
use App\Contracts\ZoneServiceInterface;
use App\ViewModels\InventoryIndexViewModel;
use App\ViewModels\InventoryShowViewModel;

class InventoryController extends Controller
{
    /**
     * Constructor del controlador.
     * 
     * @param UserServiceInterface $userService Servicio de usuarios
     * @param ActionServiceInterface $actionService Servicio de acciones
     * @param ZoneServiceInterface $zoneService Servicio de zonas
     */
    public function __construct(
        private UserServiceInterface $userService,
        private ActionServiceInterface $actionService,
        private ZoneServiceInterface $zoneService,
    ) {
    }

    /**
     * Muestra el inventario completo del usuario autenticado.
     * 
     * @return \Illuminate\View\View Vista con el inventario del usuario
     */
    public function index()
    {
        $user = auth()->user();
        $inventory = Inventory::where('user_id', $user->id)
            ->with(['inventions.inventionType', 'inventoryMaterials.material.materialType'])
            ->first();

        // Agrupo inventos y materiales por tipo 
        $inventionsByType = $inventory->inventions->where('available', true)->groupBy('inventionType.name');
        $materialsByType = $inventory->inventoryMaterials->groupBy('material.materialType.name');

        $total_materials = $inventory->inventoryMaterials->sum('quantity');
        $total_inventions = $inventory->inventions->where('available', true)->count();

        $viewModel = new InventoryIndexViewModel(
            inventory: $inventory,
            inventionsByType: $inventionsByType,
            materialsByType: $materialsByType,
            totalMaterials: $total_materials,
            totalInventions: $total_inventions,
        );

        // Obtener la zona actual del usuario
        $zone_id = $this->actionService->getLastActionableByType('Mover');
        $zone = $zone_id ? $this->zoneService->getZone($zone_id) : null;

        return view('inventories.index', compact('viewModel', 'inventory', 'zone'));
    }

    /**
     * Muestra los inventos de un tipo específico del inventario del usuario.
     * 
     * @param string $id ID del tipo de invento
     * @return \Illuminate\View\View Vista con los inventos del tipo especificado
     */
    public function show(string $id)
    {
        $user = auth()->user();
        $inventory_id = Inventory::where('user_id', $user->id)->first()->id;
        $inventions = Invention::where('inventory_id', $inventory_id)
                                ->where('invention_type_id', $id)
                                ->with('inventionType')
                                ->get();

        // Obtener el tipo de invento directamente
        $inventionType = \App\Models\InventionType::findOrFail($id);

        $viewModel = new InventoryShowViewModel(
            inventions: $inventions,
            user: $user,
        );

        return view('inventories.show', compact('viewModel', 'inventions', 'user', 'inventionType'));
    }

}
