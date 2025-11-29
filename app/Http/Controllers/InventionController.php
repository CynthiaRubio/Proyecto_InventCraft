<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Invention;

class InventionController extends Controller
{
    /**
     * Muestra una lista de todos los inventos.
     * 
     * @return \Illuminate\View\View Vista con la lista de inventos
     */
    public function index()
    {
        return view('inventions.index', ['inventions' => Invention::all()]);
    }

    /**
     * Muestra los detalles de un invento específico.
     * 
     * @param string $id ID del invento
     * @return \Illuminate\View\View Vista con los detalles del invento
     */
    public function show(string $id)
    {
        $invention = Invention::with(['material' , 'inventionType.building'])->findOrFail($id);

        return view('inventions.show', compact('invention'));
    }

    /**
     * Elimina un invento del sistema.
     * 
     * @param string $id ID del invento a eliminar
     * @return \Illuminate\Http\RedirectResponse Redirección a la lista de inventos con mensaje de éxito
     */
    public function destroy(string $id)
    {
        $invention = Invention::findOrFail($id);
        $invention->delete();

        return redirect()->route('inventions.index')
            ->with('success', "El invento $invention->name ha sido eliminado");
    }

}
