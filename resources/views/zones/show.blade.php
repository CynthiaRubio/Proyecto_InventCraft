@extends('layouts.full')

@section('title', "Zona {$viewModel->zone->name}")

@section('content')
    <x-page-title 
        title="{{ $viewModel->zone->name }}" 
        gradient="linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)"
        borderColor="#4facfe"
    />

<div class="container mt-5">
    <div class="row align-items-center">
        <div class="col-md-6">
            @if($viewModel->soundUrl)
                <audio id="zoneSound" autoplay loop>
                    <source src="{{ $viewModel->soundUrl }}" type="audio/mpeg">
                    Tu navegador no soporta la reproducción de audio.
                </audio>
            @endif

            <h3 class="text-center mt-4 mb-3">Recursos que puedes encontrar</h3>

            <div class="accordion" id="accordionResources">
                <x-accordion-item 
                    title="Materiales" 
                    :count="$viewModel->zone->materials->count()"
                    :index="0"
                    type="material"
                >
                    <ul class="list-group">
                        @foreach($viewModel->zone->materials as $material)
                            <li class="list-group-item">
                                <a href="{{ route('materials.show', $material->id) }}" class="text-decoration-none text-primary fw-semibold">
                                    {{ $material->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </x-accordion-item>

                <x-accordion-item 
                    title="Inventos" 
                    :count="$viewModel->zone->inventionTypes->count()"
                    :index="1"
                    type="invention"
                >
                    <ul class="list-group">
                        @foreach($viewModel->zone->inventionTypes as $invention)
                            <li class="list-group-item">
                                <a href="{{ route('inventionTypes.show', $invention->id) }}" class="text-decoration-none text-primary fw-semibold">
                                    {{ $invention->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </x-accordion-item>
            </div>

            <div class="text-center mt-4">
                @if($viewModel->moveTime < 1)
                    <form action="{{ route('farmZone') }}" method="POST">
                        @csrf
                        <input type="hidden" value="{{ $viewModel->zone->id }}" name="zone_id"> 
                        <label for="farmTime" class="form-label">Tiempo dedicado a explorar la zona</label>
                        <input type="number" id="farmTime" name="farmTime" value="30" min="30" max="600" class="form-control mb-3">
                        <button type="submit" class="btn btn-lg shadow fw-bold" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; border: none;">
                            🗺️ Explorar esta Zona
                        </button>
                    </form>
                @else
                    <h3 class="text-center mb-3">Tiempo de viaje: {{ $viewModel->moveTime }} minutos</h3>
                    <form action="{{ route('moveZone') }}" method="POST">
                        @csrf
                        <input type="hidden" value="{{ $viewModel->zone->id }}" name="zone_id">
                        <button type="submit" class="btn btn-lg shadow fw-bold" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; border: none;">
                            🚶 Mover a esta Zona
                        </button>
                    </form>
                @endif
            </div>

            <div class="text-center mt-3">
                <x-action-button 
                    href="{{ route('zones.index') }}" 
                    text="Regresar al mapa" 
                    variant="outline-primary"
                />
            </div>
        </div>

        <div class="col-md-6 text-center">
            <img src="{{ asset('images/zones/' . $viewModel->zone->name . '.png') }}" 
                 alt="{{ $viewModel->zone->name }}" 
                 class="floating-image">
        </div>
    </div>
</div>

@endsection
