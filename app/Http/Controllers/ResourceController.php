<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActionZone;
use App\Models\Resource;
use App\Models\Material;
use App\Models\InventionType;
use Illuminate\Support\Facades\Auth;

class ResourceController extends Controller
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
    public function show(string $id)
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

    public function collectResources($id)
{
    /* Obtenemos el usuari autenticado */
    $user = auth()->user();

    /* El usuario tiene que estar en una zona para poder recolectar, comprobar la zona del usuario */
    $zone_user = ActionZone::where('user_id' , $user->_id)->get();

    /* Y habría que comprobar que es la zona que nos llega y en la que esta el usuario es la misma */
    $zone = Zone::findOrFail($id);

    if (!$zone_user || $zone_user->zone_id !== $zone->id) {
        return redirect()->route('zones.show', $zone->id)->with('error', 'No estás en esta zona. No puedes recolectar recursos aquí.');
    }

    /* Obtenemos todos los materiales y tipos de inventos disponibles en la zona */
    $materials = Material::where('zone_id', $zone->id)->get();
    $invention_types = InventionType::where('zone_id', $zone->id)->get();

    /* Determinar de forma aleatoria los materiales y los inventos que recoge */
    $max_materials = (count($materials) / 2 )+1; // Máximo de materiales a recolectar
    $max_inventions = 2; // Máximo de inventos a recolectar

    // Recolectar materiales de manera aleatoria
    $random_materials = $materials->random(min($max_materials, $materials->count()));

    // Recolectar inventos de manera aleatoria
    $random_inventions = $invention_types->random(min($max_inventions, $invention_types->count()));


    /* Guardar los recursos en la tabla resources */
    foreach ($materials_ as $material) {
        Resource::create([
            'user_id' => $user->_id,
            'resourceable_id' => $material->id,
            'resourceable_type' => Material::class,
            'zone_id' => $zone->id,
        ]);
    }

    // Guardar los recursos de los inventos en la tabla `resources`
    foreach ($inventions as $invention) {
        Resource::create([
            'user_id' => $user->_id,
            'resourceable_id' => $invention->id,
            'resourceable_type' => Invention::class,
            'zone_id' => $zone->id,
        ]);
    }

    return redirect()->route('zones.show')->with('success', 'Has recolectado recursos de la zona ' . $zone->name);
}

}
