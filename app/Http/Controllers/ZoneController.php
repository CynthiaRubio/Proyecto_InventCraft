<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Models\Zone;
use App\Models\Material;
use App\Models\InventionType;
use App\Services\ActionManagementService;
use App\Services\UserManagementService;
use App\Services\FreeSoundService;

class ZoneController extends Controller
{
    public function __construct(
        private UserManagementService $user_service,
        private ActionManagementService $action_service,
        private FreeSoundService $free_service,
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

        $zoneSounds = [
            'Pradera' => 'meadow birds',
            'Bosque' => 'forest',
            'Selva' => 'jungle birds',
            'Desierto' => 'desert wind',
            'Montaña' => 'mountain wind',
            'Lagos' => 'lake water',
            'Polo Norte' => 'arctic wind',
            'Glaciar de Montaña' => 'glacier ice',
            'Polo Sur' => 'antarctica'
        ];
        $soundQuery = $zoneSounds[$zone->name] ?? 'nature ambience'; 

        //Si ya hay sonido guardado en la sesion no volvemos a usar la api
        if (Session::has('zonesound' . $zone->id)) {
            $sound_url = Session::get('zonesound' . $zone->id);
        
        } else {
            // Sino, buscamos el sonido en la api
            $sound_url = $this->free_service->getSoundUrl($soundQuery);
            Session::put('zonesound' . $zone->id, $sound_url);
        }

        $moveTime = $this->action_service->calculateMoveTime($id);

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
