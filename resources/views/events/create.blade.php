@extends('layouts.full')

@section('title', 'Crear Evento')

@section('content')

<div class="container mt-5">
    
    <h2 class="text-center mb-4" style="background: linear-gradient(to right,rgb(82, 146, 84), #81C784); color: white; padding: 10px; border-radius: 8px;">
        ➕ Crear Evento
    </h2>

    <div class="row justify-content-center">
        
        <div class="col-md-6">
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

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-outline-primary btn-lg">✅ Guardar</button>
                    <a href="{{ route('events.index') }}" class="btn btn-outline-secondary btn-lg">⬅️ Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
