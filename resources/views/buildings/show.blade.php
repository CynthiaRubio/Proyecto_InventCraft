@extends('layouts.full')

@section('title', "Edificio $building->name")

@section('content')

<h2 class="text-center mb-4">{{$building->name}}</h2>

<div class="container mt-5">

    <div class="row align-items-center">

        <div class="col-md-6 text-center">
            <img src="{{ asset('images/buildings/' . $building->name . '.webp') }}" alt="{{ $building->name }}"
                class="img-fluid rounded shadow">
        </div>

        <div class="col-md-6 text-center">
            <dl>
                <dt>Nombre:</dt>
                <dd>{{$building->name}}</dd>
            </dl>
            <dl>
                <dt>Descripción:</dt>
                <dd>{{$building->description}}</dd>
            </dl>
            <dl>
                <dt>Coordenadas:</dt>
                <dd>[{{$building->coord_x}} , {{$building->coord_y}}]</dd>
            </dl>
            <dl>
                <dt>Tipos de inventos necesarios para construir este edificio:</dt>
                <dd>
                    <ul>
                    @foreach($inventions_need as $invention)
                        <li class="">{{$invention->name}}</li>
                    @endforeach
                </ul>
                </dd>
            </dl>

            <div class="mb-4 mt-4">
                <h5 class="text-center">Nivel actual: {{$actual_level}} / 10 con una eficiencia de {{$building->efficiency}}</h5>
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
        
            <div class="text-center">
                @if($actual_level > 0)
                <a href="{{route('actionBuildings.create.withBuilding' , $building) }}" class="btn btn-warning">Mejora este edifcio</a>
                @else
                <a href="{{route('actionBuildings.create.withBuilding' , $building) }}" class="btn btn-warning">Construir este edifcio</a>
                @endif
            </div>
            <br>
            <div class="text-center">
                <a href="{{route('buildings.index')}}" class="btn btn-warning">Regresar al Listado de Edificios</a>
            </div>
        </div class="col-md-6 text-center">
            
    </div>
</div>

@endsection