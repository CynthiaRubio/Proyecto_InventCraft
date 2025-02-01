@extends('layouts.full')

@section('title', 'Edificios')

@section('content')

<h2 class="text-center mb-4" style="font-size: 3rem; font-weight: bold; color: #3498db; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);">
    Edificios
</h2>

<div class="row g-3">

    @foreach($buildings as $building)
        <div class="col-12 col-md-4">

            <div class="card h-100 shadow-sm">
                <img src="{{ asset('images/buildings/' . $building->name . '.webp') }}" class="card-img-top" alt="Imagen de {{ $building->name }}">
                <div class="card-body">
                    <h5 class="card-title text-uppercase text-dark">{{ $building->name }}</h5>
                    <p class="card-text text-muted">{{ $building->description }}</p>
                </div>
                <div class="card-footer text-center bg-white border-top-0">
                    <a href="{{ route('buildings.show' , $building->id) }}" class="btn btn-outline-primary btn-hover-grow">
                        Ver Edificio
                    </a>
                </div>
            </div>

        </div>
    @endforeach
</div>

@endsection

