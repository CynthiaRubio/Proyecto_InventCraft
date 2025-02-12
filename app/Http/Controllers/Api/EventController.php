<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Models\Event;
use App\Models\Zone;

class EventController extends Controller
{
    /**
     * Devuelve la lista de eventos en formato JSON.
     */
    public function index()
    {
        $events = Event::with('zone')->get();
        return response()->json(['events' => $events], 200);
    }

    /**
     * Devuelve un evento específico en JSON.
     */
    public function show($id)
    {
        $event = Event::with('zone')->find($id);

        if (!$event) {
            return response()->json(['error' => 'Evento no encontrado'], 404);
        }

        return response()->json(['event' => $event], 200);
    }

    /**
     * Guarda un nuevo evento y lo devuelve en JSON.
     */
    public function store(Request $request)
    {
        $request->validate([
            'zone_id' => 'required|exists:zones,_id', 
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $event = Event::create($request->all());

        return response()->json(['message' => 'Evento creado correctamente', 'event' => $event], 201);
    }

    /**
     * Actualiza un evento y devuelve la respuesta en JSON.
     */
    public function update(Request $request, $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['error' => 'Evento no encontrado'], 404);
        }

        $request->validate([
            'zone_id' => 'required|exists:zones,_id', 
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $event->update($request->all());

        return response()->json(['message' => 'Evento actualizado correctamente', 'event' => $event], 200);
    }

    /**
     * Elimina un evento y devuelve una confirmación en JSON.
     */
    public function destroy($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['error' => 'Evento no encontrado'], 404);
        }

        $event->delete();

        return response()->json(['message' => 'Evento eliminado correctamente'], 200);
    }
}
