<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ActionManagementService;
use App\Services\ResourceManagementService;
//use App\Models\Zone;

class ActionController extends Controller
{

    public function __construct(
        private ActionManagementService $action_service,
        private ResourceManagementService $resource_service,
        //EventCalculateService $eventService
    ) {
    }

    /**
     * Función que calcula el tiempo de desplazamiento a otra zona
    */
    public function moveZone(Request $request)
    {
        $user = auth()->user();
        $moveTime = $this->action_service->calculateMoveTime($request->zone_id);
        $action = $this->action_service->createAction('Mover',$request->zone_id,'Zone',$moveTime);

        if($action){
            $zone_name = $this->action_service->getZone($request->zone_id)->name;
            return view('actions.wait' , compact( 'zone_name' , 'moveTime'));
        } else {
            return redirect()->route('zones.index')->with('error', "Las carreteras están cortadas. No puedes viajar a esta zona.");
        }
        
    }

    /**
     * Función para recolectar
     */
    public function farmZone(Request $request)
    {
        //$result = $this->resource_service->calculateFarm($request->zone_id, $request->farmTime);

        //if(count($result) > 0){
            $zone = $this->action_service->getZone($request->zone_id);
            return redirect()->route('zones.index')->with('success' , "Estas explorando $zone->name durante $request->farmTime minutos.");
        //} else {
        //    return redirect()->route('zones.index')->with('error' , "Ohhh que mala suerte! No has recolectado nada.");
        //}
        
    }


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

}
