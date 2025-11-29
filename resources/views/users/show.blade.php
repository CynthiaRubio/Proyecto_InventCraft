@extends('layouts.full')

@section('title', 'Mi Perfil')

@section('content')

<x-page-title 
    title="👤 Tu perfil" 
    gradient="linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)"
    borderColor="#4facfe"
/>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <!-- Tarjeta de Perfil -->
            <div class="card text-light border-0 rounded shadow-lg mb-4" style="background: url('{{ asset('images/avatars/profile-bg.webp') }}') center/cover no-repeat; border-top: 4px solid #4facfe !important;">
                <div class="card-body p-4" style="background: rgba(0,0,0,0.6); border-radius: 10px;">
                    <div class="row align-items-center">
                        <!-- Sección Izquierda: Avatar, Nombre y Email -->
                        <div class="col-12 col-md-4 text-center mb-4 mb-md-0">
                            <img src="{{ $viewModel->avatarPath() }}" alt="Perfil de {{ $viewModel->userName() }}" 
                                 class="rounded-circle border border-4 mb-3 shadow" 
                                 style="border-color: #4facfe !important; width: 150px; height: 150px; object-fit: cover;">
                            <h2 class="fw-bold mb-2 text-white">{{ $viewModel->userName() }}</h2>
                            <p class="fw-bold mb-0 text-white">{{ $viewModel->userEmail() }}</p>
                        </div>

                        <!-- Sección Derecha: Información del Jugador -->
                        <div class="col-12 col-md-8">
                            <!-- Botones de Acción -->
                            <div class="d-flex gap-2 flex-wrap mb-4">
                                @if($viewModel->hasUnassignedPoints())
                                    <x-action-button 
                                        :href="route('users.points', $user)" 
                                        text="⭐ Puntos del Jugador ({{ $viewModel->unassignedPoints() }})" 
                                        variant="warning"
                                        size="sm"
                                    />
                                @endif
                                <x-action-button 
                                    :href="route('users.avatar', $user->id)" 
                                    text="Cambiar Avatar" 
                                    variant="warning"
                                    size="sm"
                                />
                            </div>

                            <!-- Barra de Nivel -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold text-white">Nivel {{ $viewModel->level() }}</span>
                                </div>
                                <x-progress-bar 
                                    :value="$viewModel->levelProgress()" 
                                    :max="100" 
                                    :label="''" 
                                    color="primary"
                                    height="25px"
                                    :animated="true"
                                    :striped="true"
                                />
                            </div>

                            <!-- Barra de Experiencia -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold text-white">Experiencia</span>
                                    <span class="text-white">{{ $viewModel->experience() }} EXP</span>
                                </div>
                                <x-progress-bar 
                                    :value="$viewModel->experienceProgress()" 
                                    :max="100" 
                                    :label="''" 
                                    color="warning"
                                    height="25px"
                                    :animated="true"
                                    :striped="true"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de Estadísticas -->
            <x-section-header 
                title="📊 Mis Estadísticas" 
                bgColor="#4facfe"
                size="1.5rem"
            />

            <div class="row g-3 mb-4">
                @if (!$viewModel->hasStats())
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <p class="mb-0">No hay estadísticas disponibles.</p>
                        </div>
                    </div>
                @else
                    @foreach ($viewModel->stats() as $userStat)
                        @php
                            $stat = $userStat->stat;
                        @endphp
                        <div class="col-12 col-sm-6 col-md-3">
                            <x-stat-card 
                                :name="$stat->name ?? 'Sin nombre'"
                                :description="$stat->description ?? null"
                                :value="$userStat->value" 
                            />
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Botones de Acción -->
            <div class="text-center mt-4">
                <div class="d-flex gap-2 justify-content-center flex-wrap">
                    @if($viewModel->hasZone())
                        <x-action-button 
                            :href="route('zones.show', $viewModel->zoneId())" 
                            :text="'🗺️ Ir a la zona ' . $viewModel->zoneName()" 
                            variant="outline-primary"
                        />
                    @else
                        <x-action-button 
                            :href="route('zones.index')" 
                            text="🗺️ Ir al mapa" 
                            variant="outline-primary"
                        />
                    @endif

                    <x-action-button 
                        :href="route('inventories.index')" 
                        text="📦 Inventario" 
                        variant="outline-primary"
                    />
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
