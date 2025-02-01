@extends('layouts.basic')

@section('title', "Invento tipo $invention->inventionType->name")

@section('content')
<h2 class="text-center mb-4">{{ $invention->name }}</h2>
    <div class="container mt-5">

        <div class="row align-items-center">

            <div class="col-md-6">
                <ul>
                    <dl>
                        <dt>Nombre del invento:</dt>
                        <dd>{{$invention->name}}</dd>
                        <dt>Invento tipo:</dt>
                        <dd>{{$invention->inventionType->name}}</dd>
                        <dt>Material con el que se ha hecho:</dt>
                        <dd>{{$invention->material->name}}</dd>
                        <dt>Eficiencia del invento:</dt>
                        <dd>{{$invention->efficiency}}%</dd>
                        <dt>Con este invento puedes construir el edificio:<dt>
                        <dd>{{$invention->inventionType->building->name}}</dd>
                    </dl>
                </ul>
                    
                <div class="text-center">     
                    <a href="{{ route('inventionTypes.index')}}" class="btn btn-warning">Volver al listado de Tipos de Inventos</a>
                </div>
                
            </div>

            <div class="col-md-6">
                <img src="{{ asset('images/inventionTypes/' . $invention->inventionType->name . '.png') }}" alt="{{ $invention->inventionType->name }}"
                    class="floating-image">
            </div>
        </div>

    </div>
@endsection