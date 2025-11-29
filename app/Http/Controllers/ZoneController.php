<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zone;
use App\Contracts\ActionServiceInterface;
use App\Contracts\ZoneServiceInterface;
use App\Contracts\FreeSoundServiceInterface;
use App\ViewModels\ZoneIndexViewModel;
use App\ViewModels\ZoneShowViewModel;

class ZoneController extends Controller
{
    /**
     * Constructor del controlador.
     * 
     * @param ActionServiceInterface $actionService Servicio de acciones
     * @param ZoneServiceInterface $zoneService Servicio de zonas
     * @param FreeSoundServiceInterface $freeService Servicio de sonidos
     */
    public function __construct(
        private ActionServiceInterface $actionService,
        private ZoneServiceInterface $zoneService,
        private FreeSoundServiceInterface $freeService,
    ) {
    }

    /**
     * Muestra el mapa con todas las zonas y la zona actual del usuario.
     * 
     * @return \Illuminate\View\View Vista del mapa de zonas
     */
    public function index()
    {
        $zones = Zone::all();
        $zone_id_user = $this->actionService->getLastActionableByType('Mover');
        $zone_user = Zone::find($zone_id_user);

        $viewModel = new ZoneIndexViewModel(
            zones: $zones,
            currentZone: $zone_user,
        );

        return view('zones.index', compact('viewModel', 'zones', 'zone_user'));
    }

    /**
     * Muestra los detalles de una zona específica.
     * 
     * @param string $id ID de la zona
     * @return \Illuminate\View\View Vista con los detalles de la zona
     */
    public function show(string $id)
    {
        $zone = Zone::with(['materials', 'inventionTypes'])->find($id);
        $sound_url = $this->freeService->getSound($zone);
        $moveTime = $this->zoneService->calculateMoveTime($id);

        $viewModel = new ZoneShowViewModel(
            zone: $zone,
            moveTime: $moveTime,
            soundUrl: $sound_url,
        );

        return view('zones.show', compact('viewModel', 'zone', 'moveTime', 'sound_url'));
    }

}
