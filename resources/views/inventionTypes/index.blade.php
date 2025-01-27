
@extends('layouts.full') <!-- Hereda de la plantilla de layouts app -->

@section('title', 'Tipos de Inventos') <!-- Le pasamos el titulo a la plantilla -->

@section('content') <!-- Le pasamos el contenido a la plantilla -->

    <h2 class="text-center">Tipos de Inventos</h2>
    
    <div class="table-responsive mb-4">
        <table class="table table-bordered text-center">
            <thead class="table-light">
                <tr>
                    <th class="text-center">Nombre</th>
                    <th class="text-center">Ver inventos de este tipo</th>
                    <th class="text-center">Crear invento</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inventionTypes as $type)
                <tr>
                    <td>{{$type->name}}</td>
                    <td><a href="{{ route ('inventionTypes.show' , $type->id)}}" class="btn btn-warning">Ver inventos</a></td>
                    <td><a href="{{route('inventions.create.withType' , $type )}}" class="btn btn-warning">Crear un invento de este tipo</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection