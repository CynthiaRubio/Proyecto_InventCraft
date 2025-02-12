@extends('layouts.full')

@section('title', "Edificio $building->name")

@section('content')

<h2 class="text-center mb-4" style="font-size: 3rem; font-weight: bold; color: #fff; background: linear-gradient(45deg, #ff6f61, #ff9a8b); border-radius: 8px; padding: 10px 20px; border: 3px solid #ff6f61;">
    {{ $building->name }}
</h2>

<div class="container mt-5">

    <div class="row align-items-center">

        <!-- Imagen del Edificio -->
        <div class="col-md-6 text-center mb-4 mb-md-0">
            <img src="{{ asset('images/buildings/' . $building->name . '.webp') }}" alt="{{ $building->name }}"
                class="img-fluid rounded shadow-lg">
        </div>

        <!-- Información del Edificio -->
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Nombre:</dt>
                        <dd class="col-sm-8">{{$building->name}}</dd>

                        <dt class="col-sm-4">Descripción:</dt>
                        <dd class="col-sm-8">{{$building->description}}</dd>

                        <dt class="col-sm-4">Coordenadas:</dt>
                        <dd class="col-sm-8">[{{$building->coord_x}} , {{$building->coord_y}}]</dd>

                        <dt class="col-sm-4">Tipos de inventos necesarios:</dt>
                        <dd class="col-sm-8">
                            <ul>
                                @foreach($building->inventionTypes as $invention)
                                    <li>{{$invention->name}}</li>
                                @endforeach
                            </ul>
                        </dd>

                        <dt class="col-sm-4">Aumenta:</dt>
                        <dd class="col-sm-8">
                            <ul>
                                @foreach($building->stats as $stat)
                                    <li>{{$stat->stat->name}} en {{$stat->value}} puntos</li>
                                @endforeach
                            </ul>
                        </dd>
                    </dl>
                </div>
            </div>

            <!-- Barra de progreso -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="text-center">Nivel actual: {{$actual_level}} / 10 con eficiencia de {{$efficiency}}%</h5>
                    <div class="progress" style="height: 30px;">
                        @php
                            $progress = ($actual_level / 10) * 100;
                            $bgColor = 'bg-danger';

                            if ($actual_level >= 8) {
                                $bgColor = 'bg-success';
                            } elseif ($actual_level >= 5) {
                                $bgColor = 'bg-warning';
                            }
                        @endphp
                        <div class="progress-bar {{$bgColor}} progress-bar-striped progress-bar-animated" 
                            role="progressbar" 
                            style="width: {{$progress}}%;" 
                            aria-valuenow="{{$progress}}" 
                            aria-valuemin="0" 
                            aria-valuemax="100">
                            Nivel {{$actual_level}}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="text-center">
                @if($actual_level > 0)
                    <a href="{{route('createBuilding' , $building) }}" class="btn btn-outline-danger btn-lg w-100 mb-3">
                        Mejorar este edificio
                    </a>
                @else
                    <a href="{{route('createBuilding' , $building) }}" class="btn btn-outline-danger btn-lg w-100 mb-3">
                        Construir este edificio
                    </a>
                @endif
            </div>

            <div class="text-center">
                <a href="{{route('buildings.index')}}" class="btn btn-outline-danger btn-lg w-100">
                    Regresar al Listado de Edificios
                </a>
            </div>

        </div>
    </div>
</div>

@endsection






