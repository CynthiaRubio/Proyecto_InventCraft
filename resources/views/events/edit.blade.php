@extends('layouts.full')

@section('title', 'Editar Evento')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">✏️ Editar Evento</h2>

    <form action="{{ route('events.update', $event->_id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Nombre del Evento:</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ $event->name }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Descripción:</label>
            <textarea id="description" name="description" class="form-control">{{ $event->description }}</textarea>
        </div>

        <div class="mb-3">
            <label for="zone_id" class="form-label">Zona:</label>
            <select id="zone_id" name="zone_id" class="form-control" required>
                @foreach($zones as $zone)
                    <option value="{{ $zone->_id }}" {{ $event->zone_id == $zone->_id ? 'selected' : '' }}>
                        {{ $zone->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">✅ Guardar Cambios</button>
        <a href="{{ route('events.index') }}" class="btn btn-secondary">⬅️ Cancelar</a>
    </form>
</div>
@endsection
