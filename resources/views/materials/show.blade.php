@extends('layouts.basic')

@section('title', "material->name")

@section('content')
<h2 class="text-center mb-4">{{$material->name}}</h2>
<div class="container mt-5">

    <div class="row align-items-center">

        <div class="col-md-6">
            <div class="text-center">
                <ul>
                    <dl>
                        <dt>Nombre:</dt>
                        <dd>{{$material->name}}</dd>
                        <dt>Descripción:</dt>
                        <dd>{{$material->description}}</dd>
                        <dt>Eficiencia:</dt>
                        <dd>{{$material->efficiency}}%</dd>
                        <dt>Lo puedes encontrar en la zona:</dt>
                        <dd>{{$material->zone->name}}</dd>
                        <dt>Te servirá para crear inventos tipo:</dt>
                        <ul>
                            @foreach($material->materialType->inventionTypes as $inventionType)
                                <li class="list-unstyled">{{$inventionType->name}}</li>
                            @endforeach
                        </ul>
                    </dl>
                </ul>
                <div class="text-center">
                    <a href="{{ route('materialTypes.index')}}"class="btn btn-warning">Volver al listado de materiales</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <img src="{{ asset('images/materialTypes/' . $material->materialType->name . '.png') }}" alt="{{ $material->materialType->name }}" class="img-fluid mb-3" style="width: 250px; height: auto;">
        </div>
        
    </div>
</div>

<h2 class="text-center mb-4">{{ $material->name }}</h2>

<div class="container mt-5">
    <div class="row align-items-start">

        <div class="col-md-6">
            <div class="card p-4">
                <h3 class="h5 mb-3">Detalles del material</h3>
                <dl class="row">
                    <dt class="col-sm-4 font-weight-bold">Nombre:</dt>
                    <dd class="col-sm-8">{{ $material->name }}</dd>
                    
                    <dt class="col-sm-4 font-weight-bold">Descripción:</dt>
                    <dd class="col-sm-8">{{ $material->description }}</dd>
                    
                    <dt class="col-sm-4 font-weight-bold">Eficiencia:</dt>
                    <dd class="col-sm-8">{{ $material->efficiency }}%</dd>
                    
                    <dt class="col-sm-4 font-weight-bold">Zona:</dt>
                    <dd class="col-sm-8">{{ $material->zone->name }}</dd>
                    
                    <dt class="col-sm-4 font-weight-bold">Sirve para inventos tipo:</dt>
                    <dd class="col-sm-8">
                        <ul class="list-unstyled">
                            @foreach($material->materialType->inventionTypes as $inventionType)
                                <li>{{ $inventionType->name }}</li>
                            @endforeach
                        </ul>
                    </dd>
                </dl>
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('materialTypes.index') }}" class="btn btn-warning btn-sm">
                    Volver al listado de materiales
                </a>
            </div>
        </div>

        <div class="col-md-6 text-center">
            <img 
                src="{{ asset('images/materialTypes/' . $material->materialType->name . '.png') }}" 
                alt="Imagen de {{ $material->materialType->name }}" 
                class="img-fluid mb-3" 
                style="max-width: 250px; height: auto;">
        </div>

    </div>
</div>

@endsection