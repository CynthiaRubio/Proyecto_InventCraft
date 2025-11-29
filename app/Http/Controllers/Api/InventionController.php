<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invention;

class InventionController extends Controller
{
    /**
     * Devuelve todos los inventos en formato JSON.
     * 
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con todos los inventos
     */
    public function index()
    {
        $inventions = Invention::all();
        return response()->json(['inventions' => $inventions], 200);
    }

    /**
     * Devuelve un invento específico con sus relaciones en formato JSON.
     * 
     * @param string $id ID del invento
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el invento o error 404
     */
    public function show(string $id)
    {
        $invention = Invention::with(['material', 'inventionType.building'])->find($id);

        if (!$invention) {
            return response()->json(['error' => 'Invento no encontrado'], 404);
        }

        return response()->json(['invention' => $invention], 200);
    }

    /**
     * Elimina un invento y devuelve confirmación en formato JSON.
     * 
     * @param string $id ID del invento a eliminar
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con confirmación o error 404
     */
    public function destroy(string $id)
    {
        $invention = Invention::find($id);

        if (!$invention) {
            return response()->json(['error' => 'Invento no encontrado'], 404);
        }

        $invention_name = $invention->name;
        $invention->delete();

        return response()->json([
            'message' => "El invento {$invention_name} ha sido eliminado",
        ], 200);
    }
}

