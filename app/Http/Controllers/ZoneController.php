<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zone;
use App\Models\Material;
use App\Models\InventionType;
use App\Services\ActionManagementService;
use App\Services\UserManagementService;

class ZoneController extends Controller
{
    protected $action_service;
    protected $user_Service;
    //protected $eventService;

    public function __construct(
        ActionManagementService $actionService,
        UserManagementService $userService,
        //EventCalculateService $eventService,
    ) {
        $this->action_service = $actionService;
        $this->user_service = $userService;
        //$this->eventService = $eventService;
    }

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
    public function show(string $id)
    {
        $zone = Zone::with(['materials' , 'inventionTypes'])->find($id);

        $user = $this->user_service->getUserById(auth()->user()->id);

        $time = $this->action_service->calculateMoveTime($user->_id, $id);

        return view('zones.show', compact('zone' , 'time'));

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

    public function mover(){
        $action_type = 'Mover';
        
    }
}
