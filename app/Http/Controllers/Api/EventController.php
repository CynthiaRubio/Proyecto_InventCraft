<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;

class EventController extends Controller
{
    /**
     * Devuelve la lista de eventos en formato JSON.
     * 
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con todos los eventos
     */
    public function index()
    {
        $events = Event::with('zone')->get();
        return response()->json(['events' => $events], 200);
    }

    /**
     * Devuelve un evento específico en formato JSON.
     * 
     * @param string $id ID del evento
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el evento o error 404
     */
    public function show(string $id)
    {
        $event = Event::with('zone')->find($id);

        if (!$event) {
            return response()->json(['error' => 'Evento no encontrado'], 404);
        }

        return response()->json(['event' => $event], 200);
    }

    /**
     * Guarda un nuevo evento y lo devuelve en formato JSON.
     * 
     * @param StoreEventRequest $request Solicitud validada con los datos del evento
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el evento creado
     */
    public function store(StoreEventRequest $request)
    {
        $event = Event::create($request->validated());

        return response()->json(['message' => 'Evento creado correctamente', 'event' => $event], 201);
    }

    /**
     * Actualiza un evento y devuelve la respuesta en formato JSON.
     * 
     * @param UpdateEventRequest $request Solicitud validada con los datos del evento
     * @param string $id ID del evento a actualizar
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el evento actualizado o error 404
     */
    public function update(UpdateEventRequest $request, string $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['error' => 'Evento no encontrado'], 404);
        }

        $event->update($request->validated());

        return response()->json(['message' => 'Evento actualizado correctamente', 'event' => $event], 200);
    }

    /**
     * Elimina un evento y devuelve una confirmación en formato JSON.
     * 
     * @param string $id ID del evento a eliminar
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con confirmación o error 404
     */
    public function destroy(string $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['error' => 'Evento no encontrado'], 404);
        }

        $event->delete();

        return response()->json(['message' => 'Evento eliminado correctamente'], 200);
    }
}
