@extends('layouts.full') <!-- Hereda de la plantilla de layouts app -->

@section('title', 'Mapa') <!-- Le pasamos el titulo a la plantilla -->

@section('content') <!-- Le pasamos el contenido a la plantilla -->

    <h2 class="text-center mb-4 fw-bold p-3 rounded-3" style="background: linear-gradient(to right,rgb(58, 132, 60), #8BC34A); color: white; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);">
        Mapa 
    </h2>
    
    <h3 class="text-center mb-4 text-success">Estás en la zona {{$zone->name}}</h3>


    <div class="row g-3">
        @foreach($zones as $zone)
            <div class="col-12 col-md-4">
                <a href="{{ route('zones.show' , $zone->id) }}" class="d-block">


                    <div class="image-container">
                        <img src="{{ asset('images/zones/' . $zone->name . '.png') }}" class="img-fluid rounded shadow" alt="{{ $zone->name }}">
                        <div class="text-overlay d-flex align-items-center justify-content-center">
                            <h5 class="text-dark text-uppercase m-0">{{ $zone->name }}</h5>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>


<style>
.image-container {
    position: relative;
    overflow: hidden;
    width: 90%;
    margin: 0 auto;
}

.image-container img {
    width: 100%;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.image-container:hover img {
    transform: translateY(-10px);
}

.text-overlay {
    position: absolute;
    font-weight:600px;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.5);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.image-container:hover .text-overlay {
    opacity: 1;
}
</style>

@endsection
