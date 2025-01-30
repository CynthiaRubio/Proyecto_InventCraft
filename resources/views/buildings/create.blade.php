@extends('layouts.basic')

@section('title', "Crea tu $building->name")

@section('content')

<h2 class="text-center mb-4">{{$building->name}}</h2>

<form action="{{ route('actionBuildings.store') }}" method="POST">
    @csrf
    <input type="hidden" value="{{$building->id}}" name="building_id">
    <input type="hidden" value="{{$level}}" name="level">
        @foreach($invention_types as $type)
            <label for="{{$type->id}}">Selecciona {{$level}} invento de tipo {{$type->name}}</label><br>
            <select id="{{ $type->id }}" name="inventions[{{ $type->id }}][]" required multiple >
                @foreach($inventions_inventory as $invention)
                    @if($invention->invention_type_id === $type->_id)
                        <option value="{{$invention->id}}" name="invention_id">{{$invention->name}}: Tiene {{$invention->efficiency}} puntos de eficiencia</option>
                    @endif
                @endforeach
            </select>
            <br>
            <br>
        @endforeach
        <br>

    <a href="{{ route('actionBuildings.store') }}"><button type="submit" class="btn btn-warning">Crear Edificio</button></a>
</form>
<br>
<a href="{{ route('buildings.index') }}" class="btn btn-warning">Volver al listado de edificios</a>

@endsection

