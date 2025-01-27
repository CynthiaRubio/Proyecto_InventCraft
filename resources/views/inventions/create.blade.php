@extends('layouts.full')

@section('title', "Creando invento tipo invento del tipo $invention_type->name")

@section('content')

<h2 class="text-center mb-4">Crea tu invento del tipo {{$invention_type->name}}</h2>

<form action="{{ route('inventions.store') }}" method="POST">
    @csrf
    <input type="hidden" value="{{$invention_type->id}}" name="invention_type_id">
    <input type="hidden" value="{{$invention_types_needed}}" name="invention_types_needed">

    <div class="mb-3">
        <label class="form-label" for="material">Selecciona el material con el que vas a crear tu {{$invention_type->name}}</label><br>
        <select class="form-select" id="material" name="material_id" required >
            <option value="" selected disabled>-- Selecciona un material --</option>
            @foreach($user_materials as $material)
                <option value="{{$material->_id}}">{{$material->name}}: {{$material->efficiency}} puntos de eficiencia</option>
            @endforeach
        </select>
    </div>
    

    @foreach($invention_types_needed as $inventions_needed)
        <div class="mb-3">
            <label class="form-label" for="{{$inventions_needed}}">Selecciona {{$inventions_needed->quantity}} invento de tipo {{$inventions_needed->inventionTypeNeed->name}}</label><br>
            <select class="form-select" id="{{ $inventions_needed }}" name="inventions[{{ $inventions_needed->invention_type_need_id }}][]" multiple >
            
            @foreach($user_invention_by_type[$inventions_needed->invention_type_need_id] as $invention)
                <option value="{{$invention->id}}" name="invention_id">{{$invention->name}}: {{$invention->efficiency}} puntos de eficiencia</option>
            @endforeach
            </select>
            
        </div>
    @endforeach

    <input type="submit" name="enviar" value="Crea tu invento" class="btn btn-warning text-center">
    <br><br>
</form>

<a href="{{ route('inventionTypes.index') }}" class="btn btn-warning text-center">Volver al listado de inventos</a>

@endsection

