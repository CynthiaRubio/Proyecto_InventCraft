<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventionType;
use App\Models\Invention;

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
     * Muestra una lista de todos los tipos de inventos.
     * 
     * @return \Illuminate\View\View Vista con la lista de tipos de inventos
     */
    public function index()
    {
        $inventionTypes = InventionType::all();

        // Si se necesita, se podría precargar los tipos necesarios usando $invention_type->id
        return view('inventionTypes.index', compact('inventionTypes'));
    }

    /**
     * Muestra los detalles de un tipo de invento específico.
     * 
     * @param string $id ID del tipo de invento
     * @return \Illuminate\View\View Vista con los detalles del tipo de invento
     */
    public function show(string $id)
    {
        $invention_type = InventionType::findOrFail($id);

        return view('inventionTypes.show', compact('invention_type'));
    }
    
}
