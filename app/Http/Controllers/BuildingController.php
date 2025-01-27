<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\InventionType;
use App\Models\BuildingStat;
use App\Models\ActionBuilding;

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
        $building = Building::findOrFail($id);

        // $buildingName = (Building::where('id', '$building'))->name;

        //Falta la relación con stats para saber los beneficios de construir este edificio
        //que habrá que pasarlos a la vista show en el compact

        //$building_stat = BuildingStat::where('building_id', $building->id)->first();
        //$stat = $building_stat->stat_id;

        /*
        Para la vista:
        <p>Al construir este edificio tu {{$stat->name}} aumenta en {{$building_stat->value}} puntos<p>
        */

        //Aquí faltará comprobar si el edificio ya se ha construido para que en la vista en lugar
        //del boton de construir edificio salga actualizar edificio o subir de nivel

        $inventions_need = InventionType::where('building_id', $building->_id)->get();

        $actual_level = ActionBuilding::where('building_id',$building->_id)->count();

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
