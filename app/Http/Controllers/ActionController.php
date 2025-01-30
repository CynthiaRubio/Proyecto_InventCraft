<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ActionManagementService;

class ActionController extends Controller
{
    protected $action_service;
    //protected $eventService;

    public function __construct(
        ActionManagementService $actionService,
        //EventCalculateService $eventService
    ) {
        $this->action_service = $actionService;
        //$this->eventService = $eventService;
    }

    /**
     * Función que calcula el tiempo de desplazamiento a otra zona
    */
    public function moveZone(string $zone_id)
    {
        $user = auth()->user();
        $time = $this->action_service->calculateMoveTime($user->_id, $zone_id);
        $action = $this->action_service->createAction('Mover',$zone_id,'Zone',$time);
        $zone = $this->action_service->getZone($zone_id)->name;

        if($action){
            return redirect()->route('zones.index')->with('success', "Estas de viaje a la zona $zone. El viaje dura $time minutos.");
        } else {
            return redirect()->route('zones.index')->with('error', "Las carreteras están cortadas. No puedes viajar a esta zona.");
        }
        
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
