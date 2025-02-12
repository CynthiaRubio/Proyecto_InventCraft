@extends('layouts.full')

@section('title', "Crear $invention_type->name")

@section('content')

<h2 class="text-center mb-4" style="background: linear-gradient(to right, #FF9800, #FFC107); color: white; padding: 10px; border-radius: 8px;">
    Crea tu {{$invention_type->name}}
</h2>

<div class="d-flex align-items-center mb-4">
    
    <div style="flex: 0 0 40%; padding-right: 20px;">
        <img src="{{ asset('images/inventionTypes/' . $invention_type->name . '.png') }}" alt="{{ $invention_type->name }}" class="img-fluid mb-3" style="width: 100%; height: auto;">
    </div>

    <div style="flex: 0 0 50%; padding-left: 20px;">

        <form action="{{ route('storeInvention') }}" method="POST">
            @csrf
            <input type="hidden" name="invention_type_id" value="{{$invention_type->id}}">

            <!-- material -->
            <div class="mb-3">
                <label for="material" class="form-label">Selecciona tu material de tipo {{$invention_type->materialType->name}} con el que vas a crear tu {{$invention_type->name}}</label>
                <select id="material" name="material_id" class="form-select">
                    <option value="" selected disabled >Selecciona un material</option>
                    @forelse($user_materials as $material)
                        <option value="{{ $material->material_id }}">
                            {{ $material->material->name }} ({{$material->quantity}})
                        </option>
                    @empty
                    <p>No tienes los materiales que se necesitan</p>
                    @endforelse
                </select>
            </div>

            <!-- inventos necesarios -->
            @foreach($invention_types_needed as $needed_type)
                <div class="mb-3">
                    <label class="form-label">Selecciona {{$needed_type->quantity}} invento de tipo {{$needed_type->inventionTypeNeed->name}}</label>

                    <select name="inventions[{{ $needed_type->invention_type_need_id }}][]" class="form-select" multiple>            
                        @forelse($user_inventions_by_type[$needed_type->invention_type_need_id] as $invention)
                            <option value="{{ $invention->id }}">
                                {{ $invention->name }} 
                            </option>
                        @empty
                            <p>No tienes inventos de este tipo</p>
                        @endforelse
                    </select>
                </div>
            @endforeach

            <!-- Tiempo dedicado -->
            <div class="mb-3">
                <label for="time" class="form-label">Tiempo dedicado a la creación del invento</label>
                <input type="number" id="time" name="time" value="30" min="30" max="600" step="1" class="form-control" style="width: 100px; margin: 0 auto;">
            </div>

            <!-- Botones -->
            <div class="text-center mb-3">
                <button type="submit" class="btn btn-primary btn-lg fw-bold">Crear Invento</button>
            </div>
            
            <div class="text-center">
                <a href="{{ route('inventions.index') }}" class="btn btn-outline-warning btn-lg fw-bold">Volver al listado de inventos</a>
            </div>
            
        </form>
    </div>
</div>

 

@endsection