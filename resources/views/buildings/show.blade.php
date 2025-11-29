@extends('layouts.full')

@section('title', "Edificio $building->name")

@section('content')

<x-page-title :title="$viewModel->buildingName()" />

<div class="container mt-5">

    <div class="row align-items-center">

        <!-- Imagen del Edificio -->
        <div class="col-md-6 text-center mb-4 mb-md-0">
            <img src="{{ $viewModel->imagePath() }}" alt="{{ $viewModel->buildingName() }}"
                class="img-fluid rounded shadow-lg">
        </div>

        <!-- Información del Edificio -->
        <div class="col-md-6">
            <x-info-card>
                <div class="mb-3">
                    <dt class="fw-bold">Descripción:</dt>
                    <dd>{{ $viewModel->buildingDescription() }}</dd>
                </div>

                <div class="mb-3">
                    <dt class="fw-bold">Tipos de inventos necesarios:</dt>
                    <dd>
                        <ul class="mb-0">
                            @foreach($building->inventionTypes as $inventionType)
                                <li>
                                    <a href="{{ route('inventionTypes.show', $inventionType->id) }}" style="color: #007bff; font-weight: 600; text-decoration: none;">
                                        {{ $inventionType->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </dd>
                </div>

                <div class="mb-3">
                    <dt class="fw-bold">Aumenta:</dt>
                    <dd>
                        <ul class="mb-0">
                            @foreach($building->buildingStats as $buildingStat)
                                <li>{{ $buildingStat->stat->name }} en {{ $buildingStat->value }} punto</li>
                            @endforeach
                        </ul>
                    </dd>
                </div>
            </x-info-card>

            <!-- Barra de progreso o mensaje de construcción -->
            @if($viewModel->isUnderConstruction)
                <div class="card shadow-sm mb-4">
                    <div class="card-body text-center">
                        <h5 class="text-warning mb-0">🏗️ En construcción</h5>
                    </div>
                </div>
            @else
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="text-center">Nivel actual: {{ $viewModel->actualLevel }} con eficiencia de {{ $viewModel->efficiencyPercentage() }}</h5>
                        @php
                            $progressColor = 'danger';
                            if ($viewModel->efficiency >= 80) {
                                $progressColor = 'success';
                            } elseif ($viewModel->efficiency >= 50) {
                                $progressColor = 'warning';
                            }
                        @endphp
                        <x-progress-bar 
                            :value="(int) $viewModel->efficiency" 
                            :max="100" 
                            :label="'Eficiencia ' . $viewModel->efficiencyPercentage()" 
                            :color="$progressColor"
                            height="30px"
                            :animated="true"
                            :striped="true"
                        />
                    </div>
                </div>
            @endif

            <!-- Mensaje para Estación Espacial si no se puede construir -->
            @if($viewModel->isSpaceStation() && !$viewModel->canBuildSpaceStation())
                <div class="alert alert-warning mb-4">
                    <h5 class="alert-heading">⚠️ Requisitos no cumplidos</h5>
                    <p>{{ $viewModel->spaceStationReason() }}</p>
                    @if(count($viewModel->spaceStationBuildingsStatus()) > 0)
                        <hr>
                        <p class="mb-2"><strong>Estado de los edificios:</strong></p>
                        <ul class="mb-0">
                            @foreach($viewModel->spaceStationBuildingsStatus() as $status)
                                <li>
                                    <strong>{{ $status['building'] }}:</strong> 
                                    Nivel {{ $status['level'] ?? 0 }}, 
                                    Eficiencia {{ number_format($status['efficiency'] ?? 0, 2) }}% / {{ $status['required_efficiency'] ?? 100 }}%
                                    @if(($status['level'] ?? 0) === 0 || ($status['efficiency'] ?? 0) < 100)
                                        <span class="text-danger">❌</span>
                                    @else
                                        <span class="text-success">✅</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif

            <!-- Botones de acción -->
            @if(!$viewModel->isUnderConstruction)
                <div class="text-center mb-3">
                    @if($viewModel->isSpaceStation() && !$viewModel->canBuildSpaceStation())
                        <button class="btn btn-outline-secondary btn-lg w-100" disabled>
                            No disponible - Requisitos no cumplidos
                        </button>
                    @elseif($viewModel->efficiency >= 100 && $viewModel->actualLevel > 0)
                        <button class="btn btn-outline-success btn-lg w-100" disabled>
                            ✅ Eficiencia máxima alcanzada (100%)
                        </button>
                    @else
                        <x-action-button 
                            :href="route('createBuilding', $building)" 
                            :text="$viewModel->actualLevel > 0 ? 'Mejorar este edificio' : 'Construir este edificio'" 
                            variant="outline-danger" 
                            :fullWidth="true"
                        />
                    @endif
                </div>
            @endif

            <div class="text-center">
                <x-action-button 
                    :href="route('buildings.index')" 
                    text="Regresar al Listado de Edificios" 
                    variant="outline-danger" 
                    :fullWidth="true"
                />
            </div>

        </div>
    </div>
</div>

@endsection






