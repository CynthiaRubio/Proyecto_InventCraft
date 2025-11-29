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
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Descripción:</label>
                    <textarea id="description" name="description" class="form-control">{{ old('description') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="zone_id" class="form-label">Zona:</label>
                    <select id="zone_id" name="zone_id" class="form-control" required>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}" {{ old('zone_id') == $zone->id ? 'selected' : '' }}>{{ $zone->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="loss_percent" class="form-label">Porcentaje de Pérdida (%):</label>
                    <input type="number" id="loss_percent" name="loss_percent" class="form-control" 
                           value="{{ old('loss_percent') }}" min="0" max="100" step="0.01" required>
                    <small class="form-text text-muted">Porcentaje de pérdida de materiales (0-100)</small>
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
