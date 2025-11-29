

@extends('layouts.full')

@section('title', 'Mi Inventario')

@section('content')
<div class="container mt-5">
    <x-page-title 
        :title="'📋 Inventario de ' . $viewModel->userName() . ' 📋'" 
        gradient="linear-gradient(45deg, #2196F3, #64B5F6)"
        borderColor="#2196F3"
        size="2rem"
    />
<div class="row">
    <div class="col-md-6">
        <h3 class="d-flex justify-content-center align-items-center fs-4 alert alert-info text-center fw-bold">🛠️ Inventos: {{ $viewModel->totalInventions }} 🛠️</h3>
        
        <div class="accordion">
            @forelse($viewModel->inventionsByType as $type => $inventions)
                <x-accordion-item 
                    :title="$type" 
                    :count="$inventions->count()" 
                    :index="$loop->index"
                    type="invention"
                >
                    <ul class="list-group">
                        @foreach($inventions as $invention)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-dark">{{ $invention->name }}</span>
                                <a href="{{ route('inventions.show', $invention->id) }}" class="btn btn-warning btn-sm">Ver Invento</a>
                            </li>
                        @endforeach
                    </ul>
                </x-accordion-item>
            @empty
                <p class="text-center text-danger">No hay inventos disponibles.</p>
            @endforelse
        </div>
    </div>

    <div class="col-md-6">
        <h3 class="d-flex justify-content-center align-items-center fs-4 alert alert-info text-center fw-bold">🪵 Materiales: {{ $viewModel->totalMaterials }} 🪵</h3>
        
        <div class="accordion">
            @forelse($viewModel->materialsByType as $type => $materials)
                <x-accordion-item 
                    :title="$type" 
                    :index="$loop->index"
                    type="material"
                >
                    <ul class="list-group">
                        @foreach($materials as $material)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-dark">{{ $material->material->name }}: Cantidad ({{ $material->quantity }})</span>
                                <a href="{{ route('materials.show', $material->material) }}" class="btn btn-warning btn-sm">Ver Material</a>
                            </li>
                        @endforeach
                    </ul>
                </x-accordion-item>
            @empty
                <p class="text-center text-danger">No hay materiales disponibles.</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Botones de Acción -->
<div class="row mt-5 mb-4">
    <div class="col-12">
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            @if($zone)
                <x-action-button 
                    :href="route('zones.show', $zone->id)" 
                    :text="'🗺️ Explorar Zona: ' . $zone->name" 
                    variant="outline-primary"
                />
            @else
                <x-action-button 
                    :href="route('zones.index')" 
                    text="🗺️ Explorar Mapa" 
                    variant="outline-primary"
                />
            @endif

            <x-action-button 
                :href="route('inventionTypes.index')" 
                text="🛠️ Crear Inventos" 
                variant="outline-warning"
            />

            <x-action-button 
                :href="route('buildings.index')" 
                text="🏗️ Construir Edificios" 
                variant="outline-danger"
            />
        </div>
    </div>
</div>

</div>

@endsection