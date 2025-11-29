<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaterialType;
use App\Models\Material;

class MaterialTypeController extends Controller
{
    /**
     * Muestra una lista de todos los tipos de materiales.
     * 
     * @return \Illuminate\View\View Vista con la lista de tipos de materiales
     */
    public function index()
    {
        return view('materialTypes.index', ['materialTypes' => MaterialType::all()]);
    }

    /**
     * Muestra los detalles de un tipo de material específico.
     * 
     * @param string $id ID del tipo de material
     * @return \Illuminate\View\View Vista con los detalles del tipo de material
     */
    public function show(string $id)
    {
        $materialType = MaterialType::with('materials')->findOrFail($id);

        return view('materialTypes.show', compact('materialType'));    
    }
    
}
