<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventionType;
use App\Models\Invention;

class InventionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('inventionTypes.index', ['inventionTypes' => InventionType::all()]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $invention_type = InventionType::findOrFail($id);

        $inventions = Invention::where('invention_type_id', $invention_type->id)->get();

        return view('inventionTypes.show', compact('inventions', 'invention_type'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $id)
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
