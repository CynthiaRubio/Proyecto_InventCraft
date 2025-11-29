<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Invention;
use App\Contracts\UserServiceInterface;

class InventoryController extends Controller
{
    /**
     * Constructor del controlador.
     * 
     * @param UserServiceInterface $userService Servicio de usuarios
     */
    public function __construct(
        private UserServiceInterface $userService,
    ) {
    }

    /**
     * Devuelve el inventario completo del usuario autenticado en formato JSON.
     * 
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el inventario, inventos y materiales agrupados
     */
    public function index()
    {
        $user = auth()->user();
        $inventory = Inventory::where('user_id', $user->id)
            ->with(['inventions.inventionType', 'inventoryMaterials.material.materialType'])
            ->first();

        if (!$inventory) {
            return response()->json(['error' => 'Inventario no encontrado'], 404);
        }

        // Agrupo inventos y materiales por tipo 
        $inventionsByType = $inventory->inventions->where('available', true)->groupBy('inventionType.name');
        $materialsByType = $inventory->inventoryMaterials->groupBy('material.materialType.name');

        $total_materials = $inventory->inventoryMaterials->sum('quantity');
        $total_inventions = $inventory->inventions->where('available', true)->count();

        return response()->json([
            'inventory' => $inventory,
            'inventions_by_type' => $inventionsByType,
            'materials_by_type' => $materialsByType,
            'total_materials' => $total_materials,
            'total_inventions' => $total_inventions,
        ], 200);
    }

    /**
     * Devuelve los inventos de un tipo específico del inventario del usuario autenticado.
     * 
     * @param string $id ID del tipo de invento
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con los inventos del tipo especificado
     */
    public function show(string $id)
    {
        $user = auth()->user();
        $inventory = Inventory::where('user_id', $user->id)->first();

        if (!$inventory) {
            return response()->json(['error' => 'Inventario no encontrado'], 404);
        }

        $inventions = Invention::where('inventory_id', $inventory->id)
            ->where('invention_type_id', $id)
            ->with('inventionType')
            ->get();

        return response()->json([
            'inventions' => $inventions,
            'user' => $user,
        ], 200);
    }
}

