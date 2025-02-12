<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventionType;
use App\Models\Invention;

use App\Services\InventionTypeService;

class InventionTypeController extends Controller
{

    public function __construct(
        private InventionTypeService $invention_type_service,
    ) {
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventionTypes = InventionType::all();

        // /* Cargamos solo los datos de las columnas que queremos usando las relaciones */
        // $inventionTypes->load([
        //     'inventions:id,name',
        //     'zone:id,name',
        //     'building:id,name',
        //     'inventionTypesNeed:id,invention_type_id,invention_type_need_id',
        //     'inventionTypes.inventionType:id,name',
        // ]);

        foreach($inventionTypes as $invention_type){
            
            $invention_types_needed[] = [$this->invention_type_service->getInventionsNeeded($invention_type->_id)];
            
        }
        

        return view('inventionTypes.index', compact('inventionTypes') );
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $invention_type = InventionType::findOrFail($id);

        return view('inventionTypes.show', compact('invention_type'));
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
