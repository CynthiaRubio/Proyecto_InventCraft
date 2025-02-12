@extends('layouts.full')

@section('title', 'Detalles del Evento')

@section('content')


<div class="container mt-5">
    
    <h2 class="text-center mb-4" style="background: linear-gradient(to right, #4CAF50, #81C784); color: white; padding: 10px; border-radius: 8px;">
        📌 {{ $event->name }}
    </h2>

    <div class="row align-items-center">

       
        <div class="col-md-6">
            <ul class="list-group">
                <li class="list-group-item"><strong>Descripción:</strong> {{ $event->description }}</li>
                <li class="list-group-item"><strong>Zona:</strong> {{ $event->zone->name }}</li>
            </ul>
        </div>

       
        <div class="col-md-6 text-center">
            <div class="mb-3">
                <a href="{{ route('events.index') }}" class="btn btn-outline-secondary btn-lg">⬅️ Volver</a>
            </div>
            <div class="mb-3">
                <a href="{{ route('events.edit', $event->_id) }}" class="btn btn-outline-warning btn-lg">✏️ Editar</a>
            </div>
            <div>
                <form action="{{ route('events.destroy', $event->_id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-lg" onclick="return confirm('¿Eliminar evento?')">🗑️ Eliminar</button>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection
