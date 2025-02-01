@extends('layouts.full')

@section('title', "Crear $invention_type->name")

@section('content')

<h2 class="text-center mb-4">Crea tu {{$invention_type->name}}</h2>

<form action="{{ route('inventions.store') }}" method="POST">
    @csrf
    <input type="hidden" name="invention_type_id" value="{{$invention_type->id}}">

   <!-- material -->
<div class="mb-3">
    <label for="material" class="form-label">Selecciona tu material de tipo {{$invention_type->materialType->name}} con el que vas a crear el invento</label>
    <select id="material" name="material_id" class="form-select">
        @foreach($user_materials as $material)
            <option value="{{ $material->material_id }}">
                {{ $material->material->name }} ({{$material->quantity}})
            </option>
        @endforeach
    </select>
</div>

<!--  inventos necesarios -->
@foreach($invention_types_needed as $needed)
    <div class="mb-3">
        <label class="form-label">Selecciona {{$needed->quantity}} invento de tipo {{$needed->inventionTypeNeed->name}}</label>

        <select name="inventions[{{ $needed->invention_type_need_id }}][]" class="form-select" multiple>            
                @foreach($user_invention_by_type[$needed->invention_type_need_id] as $invention)
                    <option value="{{ $invention->id }}">
                        {{ $invention->name }} 
                    </option>
                @endforeach
        </select>
    </div>
@endforeach

<label for="time" class="form-label">Tiempo dedicado a la creación del invento</label>
<input type="number" id="time" name="time" value="30" min=30 max=600 step=1 class="form-input">

    <button type="submit" class="btn btn-success">Crear Invento</button>
</form>

<br>

<a href="{{ route('inventions.index') }}" class="btn btn-warning">Volver al listado de inventos</a>

@endsection
