@extends('layouts.full') <!-- Hereda de la plantilla de layouts app -->

@section('title', "Zona $zone->name") <!-- Le pasamos el titulo a la plantilla -->

@section('content') <!-- Le pasamos el contenido a la plantilla -->

<h2 class="text-center mb-4 fw-bold p-3 rounded-3" style="background: linear-gradient(to right,rgb(58, 132, 60), #8BC34A); color: white; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);">
    {{$zone->name}}
</h2>

<div class="container mt-5">

    <div class="row align-items-center">

        <div class="col-md-6">

            <h3 class="text-center">Coordenadas: [{{$zone->coord_x}} , {{$zone->coord_y}}]</h3>
            <br>
            @if($sound_url)
                <audio id="zoneSound" autoplay loop>
                    <source src="{{ $sound_url }}" type="audio/mpeg">
                    Tu navegador no soporta la reproducción de audio.
                </audio>
            @endif
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
            
                @if($moveTime < 1)
                
                    <form action="{{ route('farmZone') }}" method="POST">
                        @csrf
                        <input type="hidden" value="{{$zone->_id}}" name="zone_id"> 
                        <label for="farmTime" class="form-label">Tiempo dedicado a explorar la zona</label>
                        <input type="number" id="farmTime" name="farmTime" value=30 min=30 max=600 class="form-input"><br><br>
                        <button type="submit" class="btn btn-warning">Explorar esta Zona</button>
                    </form>
                @else
                <h3 class="text-center">Tiempo de viaje: {{$moveTime}} minutos</h3>
                    <form action="{{ route('moveZone') }}" method="POST">
                        @csrf
                        <input type="hidden" value="{{$zone->_id}}" name="zone_id"><br> 
                        <button type="submit" class="btn btn-warning">Mover a esta Zona</button>
                    </form>
                @endif
                
            </div>

            <br>

            <div class="text-center">
                <a href="{{ route('zones.index') }}" class="btn btn-success">Regresar al mapa</a>
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
