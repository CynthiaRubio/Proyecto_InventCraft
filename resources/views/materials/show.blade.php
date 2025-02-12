@extends('layouts.basic')

@section('title', "material->name")

@section('content')

<h2 class="text-center mb-4" style="background: linear-gradient(to right, #FF9800, #FFC107); color: white; padding: 10px; border-radius: 8px;">
    {{$material->name}}
</h2>

<div class="container mt-5">
    <div class="row align-items-center">
        <div class="col-md-6">
            <table class="table">
                <tr>
                    <td><strong>Nombre:</strong></td>
                    <td>{{$material->name}}</td>
                </tr>
                <tr>
                    <td><strong>Descripción:</strong></td>
                    <td>{{$material->description}}</td>
                </tr>
                <tr>
                    <td><strong>Eficiencia:</strong></td>
                    <td>{{$material->efficiency}}%</td>
                </tr>
                <tr>
                    <td><strong>Lo puedes encontrar en la zona:</strong></td>
                    <td>{{$material->zone->name}}</td>
                </tr>
                <tr>
                    <td><strong>Te servirá para crear inventos tipo:</strong></td>
                    <td>
                        <ul>
                            @foreach($material->materialType->inventionTypes as $inventionType)
                                <li>{{$inventionType->name}}</li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
            </table>
            <div class="text-center">
                <a href="{{ route('materialTypes.index') }}" class="btn btn-warning">Volver al listado de materiales</a>
            </div>
        </div>

        <div class="col-md-6">
            <img src="{{ asset('images/materialTypes/' . $material->materialType->name . '.png') }}" alt="{{ $material->materialType->name }}" class="img-fluid mb-3" style="width: 250px; height: auto;">
        </div>
    </div>
</div>


@endsection