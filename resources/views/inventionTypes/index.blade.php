@extends('layouts.full')

@section('title', 'Tipos de Inventos')

@section('content')
    <x-page-title 
        title="Tipos de Inventos" 
        gradient="linear-gradient(to right, #FF9800, #FFC107)"
        borderColor="#FFC107"
    />

    <div class="container">
        <div class="row g-4">
            @foreach($inventionTypes as $type)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card shadow-sm h-100 invention-type-card">
                        <div class="card-body d-flex flex-column">
                            <div class="text-center mb-3">
                                <img src="{{ asset('images/inventionTypes/' . $type->name . '.png') }}" 
                                     alt="{{ $type->name }}" 
                                     class="img-fluid" 
                                     style="max-width: 200px; height: auto;">
                            </div>

                            <h5 class="card-title text-center fw-bold mb-3">{{ $type->name }}</h5>

                            <div class="card mb-3">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2 text-muted">Información</h6>
                                    <p class="mb-1">
                                        <strong>Zona:</strong> 
                                        <a href="{{ route('zones.show', $type->zone->id) }}" style="color: #007bff; font-weight: 600; text-decoration: none;">
                                            {{ $type->zone->name }}
                                        </a>
                                    </p>
                                    <p class="mb-0">
                                        <strong>Edificio:</strong> 
                                        @if($type->building)
                                            <a href="{{ route('buildings.show', $type->building->id) }}" style="color: #007bff; font-weight: 600; text-decoration: none;">
                                                {{ $type->building->name }}
                                            </a>
                                        @else
                                            Ninguno
                                        @endif
                                    </p>
                                </div>
                            </div>

                            @if($type->inventionTypesNeed->isNotEmpty())
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h6 class="card-subtitle mb-2 text-muted fw-bold">Puedes crear:</h6>
                                        <ul class="list-unstyled mb-0">
                                            @foreach($type->inventionTypesNeed as $invention)
                                                <li>
                                                    <a href="{{ route('inventionTypes.show', $invention->inventionType->id) }}" style="color: #007bff; font-weight: 600; text-decoration: none;">
                                                        {{ $invention->inventionType->name }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif

                            @if($type->inventionTypes->isNotEmpty())
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h6 class="card-subtitle mb-2 text-muted fw-bold">Necesitas:</h6>
                                        <ul class="list-unstyled mb-0">
                                            @foreach($type->inventionTypes as $invention)
                                                <li>
                                                    <a href="{{ route('inventionTypes.show', $invention->inventionTypeNeed->id) }}" style="color: #007bff; font-weight: 600; text-decoration: none;">
                                                        {{ $invention->inventionTypeNeed->name }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif

                            <div class="d-flex gap-2 justify-content-center flex-wrap mt-auto">
                                <x-action-button 
                                    :href="route('inventories.show', $type)" 
                                    text="Ver inventos" 
                                    variant="outline-warning"
                                    size="sm"
                                />
                                <x-action-button 
                                    :href="route('createInvention', $type)" 
                                    text="Crear invento" 
                                    variant="warning"
                                    size="sm"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection