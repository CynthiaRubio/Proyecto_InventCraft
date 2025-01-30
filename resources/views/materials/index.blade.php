
@extends('layouts.full') <!-- Hereda de la plantilla de layouts básica, sin la barra de navegación -->

@section('title', 'Materiales') <!-- Le pasamos el titulo a la plantilla -->

@section('content') <!-- Le pasamos el contenido a la plantilla -->
    <h2 class="text-center">Materiales</h2>

    <div class="table-responsive mb-4">
        <table class="table table-bordered text-center">
            <thead class="table-light">
                <tr>
                    <th class="text-center">Nombre</th>
                    <th class="text-center">Características del material</th>
                </tr>
            </thead>
            <tbody>
                @foreach($materials as $material)
                <tr>
                    <td>{{$material->name}}</td>
                    <td><a href="{{ route ('materials.show' , $material->id)}}" class="btn btn-warning">Ver material</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection