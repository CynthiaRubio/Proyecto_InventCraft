@extends('layouts.full')

@section('title', 'Detalles del Evento')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">📌 {{ $event->name }}</h2>

    <ul class="list-group">
        <li class="list-group-item"><strong>Descripción:</strong> {{ $event->description }}</li>
        <li class="list-group-item"><strong>Zona:</strong> {{ $event->zone->name }}</li>
    </ul>

    <div class="mt-4">
        <a href="{{ route('events.index') }}" class="btn btn-secondary">⬅️ Volver</a>
        <a href="{{ route('events.edit', $event->_id) }}" class="btn btn-warning">✏️ Editar</a>
        <form action="{{ route('events.destroy', $event->_id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Eliminar evento?')">🗑️ Eliminar</button>
        </form>
    </div>
</div>
@endsection
