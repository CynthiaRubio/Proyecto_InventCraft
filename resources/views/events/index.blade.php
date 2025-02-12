@extends('layouts.full')

@section('title', 'Lista de Eventos')

@section('content')



<div class="container mt-5">
    <h2 class="text-center mb-4 fw-bold p-3 rounded-3" style="background: linear-gradient(to right,rgb(58, 132, 60), #8BC34A); color: white; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);">
        📅 Lista de Eventos
    </h2>
    
    <div class="text-center mb-3">
        <a href="{{ route('events.create') }}" class="btn btn-success">➕ Crear Evento</a>
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
                            <a href="{{ route('events.show', $event->_id) }}" class="btn btn-outline-info btn-sm">👀 Ver</a>
                            <a href="{{ route('events.edit', $event->_id) }}" class="btn btn-outline-warning btn-sm">✏️ Editar</a>
                            <form action="{{ route('events.destroy', $event->_id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Eliminar evento?')">🗑️ Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>




    

@endsection
