@extends('layouts.basic')

@section('title', "Crea tu $building->name")

@section('content')

<h2 class="text-center mb-4" style="font-size: 3rem; font-weight: bold; color: #fff; background: linear-gradient(45deg, #ff6f61, #ff9a8b); border-radius: 8px; padding: 10px 20px; border: 3px solid #ff6f61;">
    {{ $building->name }}
</h2>

<div class="row justify-content-center">
    <div class="col-md-5"> <!-- Hacemos la columna del formulario más estrecha (col-md-5) -->
        <form action="{{ route('storeBuilding') }}" method="POST">
            @csrf
            <input type="hidden" value="{{ $building->id }}" name="building_id">
            <input type="hidden" value="{{ $building_next_level }}" name="building_level">
            
            @foreach($invention_types_needed as $type)
                <div class="mb-3">
                    <label for="inventions{{ $type->id }}" class="form-label">
                        Selecciona {{ $building_next_level }} invento(s) de tipo {{ $type->name }}
                    </label><br>

                    <select id="inventions_{{ $type->id }}" name="inventions[{{ $type->id }}][]" multiple required class="form-select">
                        @if(isset($user_inventions_by_type[$type->id]))
                            @foreach($user_inventions_by_type[$type->id] as $invention)
                                <option value="{{ $invention->id }}">{{ $invention->name }}</option>
                            @endforeach
                        @else
                            <option disabled>No tienes inventos de este tipo</option>
                        @endif
                    </select>
                </div>
            @endforeach

            <div class="text-center mb-4">
                <button type="submit" class="btn btn-warning btn-lg shadow-sm fw-bold">Crear Edificio</button>
            </div>
        </form>
    </div>

    <div class="col-md-6 d-flex justify-content-center align-items-center"> 
        <div class="text-center">
            <img src="{{ asset('images/buildings/' . $building->name . '.webp') }}" alt="Imagen de {{ $building->name }}" 
                class="img-fluid rounded shadow" style="max-height: 600px; object-fit: contain;">
        </div>
    </div>
</div>

<div class="text-center mt-5">
    <a href="{{ route('buildings.index') }}" class="btn btn-outline-warning btn-lg shadow-sm fw-bold">
        Volver al listado de edificios
    </a>
</div>

@endsection

