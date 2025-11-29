<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\MaterialType;
use App\Models\InventionType;
use App\Models\Building;
use App\Models\Zone;

class MaterialController extends Controller
{
    /**
     * Muestra una lista de todos los materiales.
     * 
     * @return \Illuminate\View\View Vista con la lista de materiales
     */
    public function index()
    {
        return view('materials.index', ['materials' => Material::all()]);
    }

    /**
     * Muestra los detalles de un material específico.
     * 
     * @param string $id ID del material
     * @return \Illuminate\View\View Vista con los detalles del material
     */
    public function show(string $id)
    {
        $material = Material::with(['materialType.inventionTypes', 'zone'])->findOrFail($id);

        return view('materials.show', compact('material')); 
    }

}
