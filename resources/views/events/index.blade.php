@extends('layouts.full')

@section('title', 'Lista de Eventos')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">📅 Lista de Eventos</h2>

    <a href="{{ route('events.create') }}" class="btn btn-success mb-3">➕ Crear Evento</a>

    @if ($events->isEmpty())
        <p class="text-center text-danger">No hay eventos disponibles.</p>
    @else
        <table class="table table-bordered">
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
                        <td>
                            <a href="{{ route('events.show', $event->_id) }}" class="btn btn-info btn-sm">👀 Ver</a>
                            <a href="{{ route('events.edit', $event->_id) }}" class="btn btn-warning btn-sm">✏️ Editar</a>
                            <form action="{{ route('events.destroy', $event->_id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar evento?')">🗑️ Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
