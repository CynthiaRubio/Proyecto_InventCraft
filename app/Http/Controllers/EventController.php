<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Zone;

class EventController extends Controller
{
    /**
     * Muestra la lista de eventos.
     */
    public function index()
    {
        $events = Event::with('zone')->get();
        return view('events.index', compact('events'));
    }

    /**
     * Muestra un evento en detalle.
     */
    public function show($id)
    {
        $event = Event::with('zone')->findOrFail($id);
        return view('events.show', compact('event'));
    }

    /**
     * Muestra el formulario para crear un nuevo evento.
     */
    public function create()
    {
        $zones = Zone::all();
        return view('events.create', compact('zones'));
    }

    /**
     * Guarda un nuevo evento en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Event::create($request->all());

        return redirect()->route('events.index')->with('success', 'Evento creado correctamente.');
    }

    /**
     * Muestra el formulario para editar un evento existente.
     */
    public function edit($id)
    {
        $event = Event::findOrFail($id);
        $zones = Zone::all();
        return view('events.edit', compact('event', 'zones'));
    }

    /**
     * Actualiza un evento en la base de datos.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $event = Event::findOrFail($id);
        $event->update($request->all());

        return redirect()->route('events.index')->with('success', 'Evento actualizado correctamente.');
    }

    /**
     * Elimina un evento.
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->route('events.index')->with('success', 'Evento eliminado correctamente.');
    }
}
