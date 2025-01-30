<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\User;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $user = auth()->user();
        $inventory= Inventory::where('user_id', $user->id)->with([
                                                                'user:name', 
                                                                'materials:id,name', 
                                                                'inventions:id,name'])->first();

        $inventory = Inventory::where('user_id', $user->id)
            ->with(['inventions.inventionType', 'materials.material'])
            ->first();

        // Agrupo inventos  y materiales por tipo 
        $inventionsByType = $inventory->inventions->groupBy('inventionType.name');
        $materialsByType = $inventory->materials->groupBy('material.materialType.name');
        return view('inventories.show', compact('inventory', 'inventionsByType', 'materialsByType'));
    
        /* Código del controlador correspondiente a la vista que esta en Código anterior */
//         $user = auth()->user();
//         $inventory = Inventory::where('id' , $user->id)->with(['inventory.materials.material' , 'inventory.inventions.inventionType'])->get();
// //dd($inventory);
//         return view('inventories.show', compact('inventory' , 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
