<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Material;

class MaterialController extends Controller
{
    /**
     * Devuelve todos los materiales en formato JSON.
     * 
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con todos los materiales
     */
    public function index()
    {
        $materials = Material::all();
        return response()->json(['materials' => $materials], 200);
    }

    /**
     * Devuelve un material específico con sus relaciones en formato JSON.
     * 
     * @param string $id ID del material
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el material o error 404
     */
    public function show(string $id)
    {
        $material = Material::with(['materialType.inventionTypes', 'zone'])->find($id);

        if (!$material) {
            return response()->json(['error' => 'Material no encontrado'], 404);
        }

        return response()->json(['material' => $material], 200);
    }
}

