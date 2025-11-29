@extends('layouts.full')

@section('title', "Crear $invention_type->name")

@section('content')

<x-page-title 
    :title="'Crea tu ' . $invention_type->name" 
    gradient="linear-gradient(135deg, #FF9800 0%, #FFC107 100%)"
    borderColor="#FFC107"
/>

<div class="container">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-5">
            <form id="invention-form" action="{{ route('storeInvention') }}" method="POST">
                @csrf
                <input type="hidden" name="invention_type_id" value="{{$invention_type->id}}">

                <!-- material -->
                <div class="mb-3">
                    <label for="material" class="form-label">Selecciona tu material de tipo {{$invention_type->materialType->name}} con el que vas a crear tu {{$invention_type->name}}</label>
                    <select id="material" name="material_id" class="form-select form-select-lg" required>
                        <option value="" selected disabled>Selecciona un material</option>
                        @forelse($user_materials as $material)
                            <option value="{{ $material->material_id }}" {{ old('material_id') == $material->material_id ? 'selected' : '' }}>
                                {{ $material->material->name }} ({{$material->quantity}})
                            </option>
                        @empty
                            <option disabled>No tienes los materiales que se necesitan</option>
                        @endforelse
                    </select>
                </div>

                <!-- inventos necesarios -->
                @foreach($invention_types_needed as $needed_type)
                    <div class="mb-3">
                        <label class="form-label">Selecciona {{$needed_type->quantity}} {{ $needed_type->quantity > 1 ? 'inventos' : 'invento' }} de tipo {{$needed_type->inventionTypeNeed->name}}</label>

                        <select name="inventions[{{ $needed_type->invention_type_need_id }}][]" class="form-select form-select-lg" multiple size="5" required>            
                            @php
                                $type_id = (string) $needed_type->invention_type_need_id;
                                $available_inventions = isset($user_inventions_by_type[$type_id]) 
                                    ? $user_inventions_by_type[$type_id]->where('available', true) 
                                    : collect();
                            @endphp
                            @forelse($available_inventions as $invention)
                                <option value="{{ $invention->id }}">
                                    {{ $invention->name }} (Eficiencia: {{ number_format($invention->efficiency, 1) }}%)
                                </option>
                            @empty
                                <option disabled>No tienes inventos disponibles de este tipo</option>
                            @endforelse
                        </select>
                        @if($available_inventions->count() < $needed_type->quantity)
                            <small class="text-danger d-block mt-1">
                                Tienes {{ $available_inventions->count() }} {{ $available_inventions->count() != 1 ? 'inventos' : 'invento' }} disponible{{ $available_inventions->count() != 1 ? 's' : '' }}, pero se requieren {{ $needed_type->quantity }} {{ $needed_type->quantity > 1 ? 'inventos' : 'invento' }}.
                            </small>
                        @endif
                    </div>
                @endforeach

                <!-- Tiempo dedicado -->
                <div class="mb-3">
                    <label for="time" class="form-label">Tiempo dedicado a la creación del invento (Mínimo 30 y máximo 600 minutos)</label>
                    <input type="number" id="time" name="time" value="30" min="30" max="600" step="1" class="form-control w-25" required>
                </div>
            </form>
        </div>

        <div class="col-md-6 d-flex justify-content-center align-items-center">
            <div class="text-center">
                <img src="{{ asset('images/inventionTypes/' . $invention_type->name . '.png') }}" 
                     alt="{{ $invention_type->name }}" 
                     class="img-fluid rounded shadow" 
                     style="max-height: 600px; object-fit: contain;">
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-4">
        <div class="col-md-11">
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <button type="submit" form="invention-form" class="btn btn-warning shadow fw-bold">
                    Crear Invento
                </button>
                <x-action-button 
                    :href="route('inventionTypes.index')" 
                    text="Volver al listado" 
                    variant="outline-warning"
                    size="md"
                />
            </div>
        </div>
    </div>
</div>

@endsection