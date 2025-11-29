<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InventionType;
use App\Contracts\InventionTypeServiceInterface;

class InventionTypeController extends Controller
{
    /**
     * Constructor del controlador.
     * 
     * @param InventionTypeServiceInterface $inventionTypeService Servicio de tipos de inventos
     */
    public function __construct(
        private InventionTypeServiceInterface $inventionTypeService,
    ) {
    }

    /**
     * Devuelve todos los tipos de inventos en formato JSON.
     * 
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con todos los tipos de inventos
     */
    public function index()
    {
        $inventionTypes = InventionType::all();
        return response()->json(['invention_types' => $inventionTypes], 200);
    }

    /**
     * Devuelve un tipo de invento específico con sus relaciones en formato JSON.
     * 
     * @param string $id ID del tipo de invento
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el tipo de invento o error 404
     */
    public function show(string $id)
    {
        $invention_type = InventionType::with(['zone', 'building', 'inventionTypes', 'inventionTypesNeed'])->find($id);

        if (!$invention_type) {
            return response()->json(['error' => 'Tipo de invento no encontrado'], 404);
        }

        return response()->json(['invention_type' => $invention_type], 200);
    }
}

