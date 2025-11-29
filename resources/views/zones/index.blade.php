@extends('layouts.full')

@section('title', 'Mapa')

@section('content')
    <x-page-title 
        title="Mapa" 
        gradient="linear-gradient(to right, rgb(58, 132, 60), #8BC34A)"
        borderColor="#8BC34A"
    />

    <div class="container">
        @if(isset($zone_user) && $zone_user)
            <h3 class="text-center mb-4 text-success">📍 Estás en la zona {{ $zone_user->name }}</h3>
        @endif

        <div class="row g-3">
        @foreach($zones as $zone)
            <div class="col-12 col-md-4">
                <a href="{{ route('zones.show', $zone->id) }}" class="d-block text-decoration-none">
                    <div class="image-container">
                        <img src="{{ asset('images/zones/' . $zone->name . '.png') }}" 
                             class="img-fluid rounded shadow" 
                             alt="{{ $zone->name }}">
                        <div class="text-overlay d-flex align-items-center justify-content-center">
                            <h5 class="text-dark text-uppercase m-0 fw-bold">{{ $zone->name }}</h5>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
        </div>
    </div>
@endsection
