<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaterialType;
use App\Models\Material;

class MaterialTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('materialTypes.index', ['materialTypes' => MaterialType::all()]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $materialType = MaterialType::with('materials')->findOrFail($id);

        return view('materialTypes.show', compact('materialType'));    
    }


    /* TODO ESTO NO LO VAMOS A IMPLEMENTAR */

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
