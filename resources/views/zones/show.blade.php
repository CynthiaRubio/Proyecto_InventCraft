@extends('layouts.full') <!-- Hereda de la plantilla de layouts app -->

@section('title', 'Zona {{$zone}}') <!-- Le pasamos el titulo a la plantilla -->

@section('content') <!-- Le pasamos el contenido a la plantilla -->

<h2 class="text-center mb-4">{{$zone->name}}</h2>
<div class="container mt-5">

    <div class="row align-items-center">

        <div class="col-md-6">

            <h3 class="text-center">Coordenadas: [{{$zone->coord_x}} , {{$zone->coord_y}}]</h3>
    <br>
            <div class="mt-">
                <h3 class="text-center">Recursos que puedes encontrar</h3>

                <div class="accordion" id="accordionResources">

                    <!--Materiales -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingMaterials">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMaterials" aria-expanded="false" aria-controls="collapseMaterials">
                                Materiales
                            </button>
                        </h2>
                        <div id="collapseMaterials" class="accordion-collapse collapse" aria-labelledby="headingMaterials" data-bs-parent="#accordionResources">
                            <div class="accordion-body">
                                <ul class="list-group">
                                    @foreach($zone->materials as $material)
                                        <li class="list-group-item">{{ $material->name }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!--Inventos -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingInventions">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseInventions" aria-expanded="false" aria-controls="collapseInventions">
                                Inventos
                            </button>
                        </h2>
                        <div id="collapseInventions" class="accordion-collapse collapse" aria-labelledby="headingInventions" data-bs-parent="#accordionResources">
                            <div class="accordion-body">
                                <ul class="list-group">
                                    @foreach($zone->inventionTypes as $invention)
                                        <li class="list-group-item">{{ $invention->name }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <br><br>
            <div class="text-center">
            
                @if($time < 1)
                <!-- TO DO Revisar el formulario que esta con GET -->
                    <form action="{{ route('zones.index') }}" method="GET">
                        @csrf
                        <button type="submit" class="btn btn-warning">Explorar esta Zona</button>
                    </form>
                @else
                <h3 class="text-center">Tiempo de viaje: {{$time}} minutos</h3>
                    <form action="{{ route('moveZone', $zone->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-warning">Mover a esta Zona</button>
                    </form>
                @endif
                
            </div>

            <br>

            <div class="text-center">
                <a href="{{ route('zones.index') }}" class="btn btn-primary">Regresar al mapa</a>
            </div>
        </div>

        <div class="col-md-6 text-center">
            <img src="{{ asset('images/zones/' . $zone->name . '.png') }}" alt="{{ $zone->name }}" class="floating-image">
        </div>
        
    </div>

</div>

<style>
.floating-image {
    animation: float 1.5s ease-in-out infinite;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-15px);
    }
}
</style>

@endsection
