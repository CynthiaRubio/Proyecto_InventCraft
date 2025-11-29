@extends('layouts.full')

@section('title', "Crea tu $building->name")

@section('content')

<x-page-title 
    title="{{ $building->name }}" 
    gradient="linear-gradient(135deg, rgb(217, 101, 90) 0%, #ff9a8b 100%)"
    borderColor="#ff6f61"
/>

<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-5"> <!-- Hacemos la columna del formulario más estrecha (col-md-5) -->
        <form id="building-form" action="{{ route('storeBuilding') }}" method="POST">
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
                                <option value="{{ $invention->id }}">{{ $invention->name}}: {{$invention->efficiency}}% de eficiencia</option>
                            @endforeach
                        @else
                            <option disabled>No tienes inventos de este tipo</option>
                        @endif
                    </select>
                </div>
            @endforeach
        </form>
    </div>

    <div class="col-md-6 d-flex justify-content-center align-items-center"> 
        <div class="text-center">
            <img src="{{ asset('images/buildings/' . $building->name . '.webp') }}" alt="Imagen de {{ $building->name }}" 
                class="img-fluid rounded shadow" style="max-height: 600px; object-fit: contain;">
        </div>
    </div>
    </div>

    <div class="row justify-content-center mt-4">
        <div class="col-md-11">
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <button type="submit" form="building-form" class="btn btn-danger shadow fw-bold">
                    Crear Edificio
                </button>
                <x-action-button 
                    :href="route('buildings.index')" 
                    text="Volver al listado" 
                    variant="outline-warning"
                    size="md"
                />
            </div>
        </div>
    </div>
</div>

@endsection

