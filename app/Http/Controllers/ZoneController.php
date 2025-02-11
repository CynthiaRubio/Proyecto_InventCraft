<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Zone;
use App\Models\Material;
use App\Models\InventionType;
use App\Services\ActionManagementService;
use App\Services\ZoneManagementService;
use App\Services\UserManagementService;
use App\Services\FreeSoundService;

class ZoneController extends Controller
{
    public function __construct(
        private UserManagementService $user_service,
        private ActionManagementService $action_service,
        private ZoneManagementService $zone_service,
        private FreeSoundService $free_service,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $zones = Zone::all();

        $zone_id_user = $this->action_service->getLastActionableByType('Mover');

        $zone_user = Zone::find($zone_id_user);

        return view('zones.index', ['zones' => $zones , 'zone' => $zone_user]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $zone = Zone::with(['materials' , 'inventionTypes'])->find($id);

        $sound_url = $this->free_service->getSound($zone);

        $moveTime = $this->zone_service->calculateMoveTime($id);

        return view('zones.show', compact('zone' , 'moveTime', 'sound_url'));

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
