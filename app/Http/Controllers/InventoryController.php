<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Invention;
use App\Models\User;

use App\Services\UserManagementService;

class InventoryController extends Controller
{
    protected $user_service;

    public function __construct(
        UserManagementService $userService,
    ) {
        $this->user_service = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
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
        $inventionsByType = $inventory->inventions->where('available' , true)->groupBy('inventionType.name');
        $materialsByType = $inventory->materials->groupBy('material.materialType.name');

        $total_materials = $inventory->materials->sum('quantity');
        $total_inventions = $inventory->inventions->where('available' , true)->count();

        return view('inventories.index', compact('inventory', 'inventionsByType', 'materialsByType', 'total_materials' , 'total_inventions'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = auth()->user();
        $inventory_id = Inventory::where('user_id' , $user->_id)->first()->id;
        $inventions = Invention::where('inventory_id', $inventory_id)
                                ->where('invention_type_id', $id)
                                ->with('inventionType')
                                ->get();

        return view('inventories.show' , compact('inventions' , 'user'));
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
