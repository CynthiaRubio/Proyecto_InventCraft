<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zone;
use App\Models\Material;
use App\Models\InventionType;

class ZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('zones.index', ['zones' => Zone::all()]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $zone = Zone::findOrFail($id);
        $materials =  Material::where('zone_id', $id)->get();
        $invention_types =  InventionType::where('zone_id', $id)->get();;

        return view('zones.show' , compact('zone', 'materials', 'invention_types') );//['zone' => $zone]);

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
    public function edit(ZoneController $zoneController)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ZoneController $zoneController)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ZoneController $zoneController)
    {
        //
    }
}
