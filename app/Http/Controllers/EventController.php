<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Zone;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;

class EventController extends Controller
{
    /**
     * Muestra la lista de eventos.
     * 
     * @return \Illuminate\View\View Vista con la lista de eventos
     */
    public function index()
    {
        $events = Event::with('zone')->get();
        return view('events.index', compact('events'));
    }

    /**
     * Muestra un evento en detalle.
     * 
     * @param string $id ID del evento
     * @return \Illuminate\View\View Vista con los detalles del evento
     */
    public function show(string $id)
    {
        $event = Event::with('zone')->findOrFail($id);
        return view('events.show', compact('event'));
    }

    /**
     * Muestra el formulario para crear un nuevo evento.
     * 
     * @return \Illuminate\View\View Vista con el formulario de creación
     */
    public function create()
    {
        $zones = Zone::all();
        return view('events.create', compact('zones'));
    }

    /**
     * Guarda un nuevo evento en la base de datos.
     * 
     * @param StoreEventRequest $request Solicitud validada con los datos del evento
     * @return \Illuminate\Http\RedirectResponse Redirección a la lista de eventos con mensaje de éxito
     */
    public function store(StoreEventRequest $request)
    {
        Event::create($request->validated());

        return redirect()->route('events.index')->with('success', 'Evento creado correctamente.');
    }

    /**
     * Muestra el formulario para editar un evento existente.
     * 
     * @param string $id ID del evento a editar
     * @return \Illuminate\View\View Vista con el formulario de edición
     */
    public function edit(string $id)
    {
        $event = Event::findOrFail($id);
        $zones = Zone::all();
        return view('events.edit', compact('event', 'zones'));
    }

    /**
     * Actualiza un evento en la base de datos.
     * 
     * @param UpdateEventRequest $request Solicitud validada con los datos actualizados
     * @param string $id ID del evento a actualizar
     * @return \Illuminate\Http\RedirectResponse Redirección a la lista de eventos con mensaje de éxito
     */
    public function update(UpdateEventRequest $request, string $id)
    {
        $event = Event::findOrFail($id);
        $event->update($request->validated());

        return redirect()->route('events.index')->with('success', 'Evento actualizado correctamente.');
    }

    /**
     * Elimina un evento de la base de datos.
     * 
     * @param string $id ID del evento a eliminar
     * @return \Illuminate\Http\RedirectResponse Redirección a la lista de eventos con mensaje de éxito
     */
    public function destroy(string $id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->route('events.index')->with('success', 'Evento eliminado correctamente.');
    }
}
