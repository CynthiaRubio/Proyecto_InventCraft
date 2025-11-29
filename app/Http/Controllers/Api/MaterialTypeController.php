<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaterialType;

class MaterialTypeController extends Controller
{
    /**
     * Devuelve todos los tipos de materiales en formato JSON.
     * 
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con todos los tipos de materiales
     */
    public function index()
    {
        $materialTypes = MaterialType::all();
        return response()->json(['material_types' => $materialTypes], 200);
    }

    /**
     * Devuelve un tipo de material específico con sus materiales en formato JSON.
     * 
     * @param string $id ID del tipo de material
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el tipo de material o error 404
     */
    public function show(string $id)
    {
        $materialType = MaterialType::with(['materials', 'inventionTypes'])->find($id);

        if (!$materialType) {
            return response()->json(['error' => 'Tipo de material no encontrado'], 404);
        }

        return response()->json(['material_type' => $materialType], 200);
    }
}

