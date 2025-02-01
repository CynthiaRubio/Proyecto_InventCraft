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
    public function __construct(
        private UserManagementService $userService,
        private ActionManagementService $actionService,
        //EventCalculateService $eventService,
    ) {}

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

        $moveTime = $this->actionService->calculateMoveTime($id);

        return view('zones.show', compact('zone' , 'moveTime'));

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
