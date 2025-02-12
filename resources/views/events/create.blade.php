@extends('layouts.full')

@section('title', 'Crear Evento')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">➕ Crear Evento</h2>

    <form action="{{ route('events.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Nombre del Evento:</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Descripción:</label>
            <textarea id="description" name="description" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label for="zone_id" class="form-label">Zona:</label>
            <select id="zone_id" name="zone_id" class="form-control" required>
                @foreach($zones as $zone)
                    <option value="{{ $zone->_id }}">{{ $zone->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">✅ Guardar</button>
        <a href="{{ route('events.index') }}" class="btn btn-secondary">⬅️ Cancelar</a>
    </form>
</div>
@endsection
