@extends('layouts.full')

@section('title', 'Lista de Eventos')

@section('content')

<x-page-title 
    title="Lista de Eventos" 
    gradient="linear-gradient(135deg, #dc3545 0%, #c82333 100%)"
    borderColor="#dc3545"
/>

<div class="container">
    <div class="text-center mb-3">
        <x-action-button 
            :href="route('events.create')" 
            text="Crear Evento" 
            variant="success"
        />
    </div>

    @if ($events->isEmpty())
        <p class="text-center text-danger">No hay eventos disponibles.</p>
    @else
        <table class="table table-bordered" style="max-width: 800px; margin: 0 auto;">
            <thead class="table-light">
                <tr>
                    <th>Nombre</th>
                    <th>Zona</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($events as $event)
                    <tr>
                        <td>{{ $event->name }}</td>
                        <td>{{ $event->zone->name }}</td>
                        <td class="text-center">
                            <x-action-button 
                                :href="route('events.show', $event->id)" 
                                text="Ver" 
                                variant="outline-info"
                                size="sm"
                            />
                            <x-action-button 
                                :href="route('events.edit', $event->id)" 
                                text="Editar" 
                                variant="outline-warning"
                                size="sm"
                            />
                            <form action="{{ route('events.destroy', $event->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Eliminar evento?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>




    

@endsection
