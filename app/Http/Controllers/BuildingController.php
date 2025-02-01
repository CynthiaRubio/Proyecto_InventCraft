<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\InventionType;
use App\Models\BuildingStat;
use App\Models\Action;

class BuildingController extends Controller
{
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
        $user = auth()->user();

        $building = Building::with(['actions:efficiency','inventionTypes'])->findOrFail($id);

        $inventions_need = InventionType::where('building_id', $building->_id)->get();

        $actual_level = Action::where('user_id', $user->_id)->where('actionable_id', $building->_id)->count();

        return view('buildings.show', compact('building','inventions_need' , 'actual_level')); //,'building_stat','stat'));
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
