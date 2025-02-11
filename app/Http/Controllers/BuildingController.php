<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\InventionType;
use App\Models\BuildingStat;
use App\Models\Stat;
use App\Models\Action;
use App\Models\ActionBuilding;

use App\Services\BuildingManagementService;

class BuildingController extends Controller
{
    public function __construct(
        private BuildingManagementService $building_service,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('buildings.index', ['buildings' => Building::all()]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $building = $this->building_service->getBuildingWithRelations($id);

        $actual_level = $this->building_service->getActualLevel($id);

        $efficiency = $this->building_service->getEfficiency($id);

        return view('buildings.show', compact('building' , 'actual_level' , 'efficiency'));
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
