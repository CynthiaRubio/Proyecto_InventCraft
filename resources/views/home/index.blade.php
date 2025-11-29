@extends('layouts.basic')

@section('title', 'InventCraft - Juego de Estrategia')

@section('content')
<div class="container-fluid px-0">
    <!-- Hero Section -->
    <div class="hero-section text-center text-white py-5" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); min-height: 60vh; display: flex; align-items: center; justify-content: center; position: relative;">
        <div class="container" style="position: relative; z-index: 1;">
            <img src="{{ asset('images/home/logo.png') }}" alt="Logo del Juego" class="img-fluid mb-4" style="max-width: 250px; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.3)); pointer-events: none; user-select: none;">
            <h1 class="display-3 fw-bold mb-3" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5); pointer-events: none; user-select: none;">¡Bienvenido a InventCraft!</h1>
            <p class="lead fs-4 mb-4" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.5); pointer-events: none; user-select: none;">Un juego de estrategia donde la exploración y la construcción te llevarán a la victoria</p>
            
            <div class="d-flex gap-3 justify-content-center flex-wrap" style="position: relative; z-index: 10;">
                <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5 shadow-lg fw-bold" style="cursor: pointer; text-decoration: none; position: relative; z-index: 100;">
                    🚀 Regístrate y comienza tu aventura
                </a>
                <a href="{{ route('login') }}" class="btn btn-light btn-lg px-5 shadow-lg fw-bold" style="cursor: pointer; text-decoration: none; position: relative; z-index: 100;">
                    🔑 Iniciar Sesión
                </a>
            </div>
            
        </div>
    </div>

    <!-- Game Overview Section -->
    <div class="container py-5">
        <div class="row mb-5">
            <div class="col-12 text-center mb-4">
                <h2 class="display-5 fw-bold mb-3" style="color: #4facfe;">¿Qué es InventCraft?</h2>
                <p class="lead text-muted">Un juego de estrategia individual donde compites por alcanzar la mayor puntuación y construir la <strong class="text-primary">Estación Espacial</strong>, representando una carrera por el desarrollo tecnológico y la exploración.</p>
            </div>
        </div>

        <!-- Features Grid -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card h-100 shadow-lg border-0 text-center" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <div class="card-body p-4">
                        <div class="mb-3" style="font-size: 3rem;">🗺️</div>
                        <h3 class="text-white fw-bold mb-3">Explora el Mapa</h3>
                        <p class="text-white">Navega por un mapa de 3x3 zonas, cada una con recursos únicos. Recolecta materiales, encuentra inventos y descubre nuevas oportunidades.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 shadow-lg border-0 text-center" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <div class="card-body p-4">
                        <div class="mb-3" style="font-size: 3rem;">🛠️</div>
                        <h3 class="text-white fw-bold mb-3">Crea Inventos</h3>
                        <p class="text-white">Combina materiales para crear inventos únicos. Cada invento te acerca más a la construcción de edificios poderosos y mejoras permanentes.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 shadow-lg border-0 text-center" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                    <div class="card-body p-4">
                        <div class="mb-3" style="font-size: 3rem;">🏗️</div>
                        <h3 class="text-white fw-bold mb-3">Construye Edificios</h3>
                        <p class="text-white">Construye y mejora edificios que otorgan bonificaciones permanentes. Alcanza el 100% de eficiencia en todos para desbloquear la Estación Espacial.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Game Mechanics -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card shadow-lg border-0" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                    <div class="card-body p-5">
                        <h3 class="text-center text-white fw-bold mb-4 display-6">🎯 Objetivo del Juego</h3>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <span class="fs-2 me-3">1️⃣</span>
                                    <div>
                                        <h5 class="text-white fw-bold">Progresa y Mejora</h5>
                                        <p class="text-white mb-0">Gana experiencia realizando acciones: desplazarte, recolectar recursos, crear inventos y construir edificios. Sube de nivel y obtén puntos para mejorar tus estadísticas.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <span class="fs-2 me-3">2️⃣</span>
                                    <div>
                                        <h5 class="text-white fw-bold">Construye la Estación Espacial</h5>
                                        <p class="text-white mb-0">Para ganar, debes alcanzar el 100% de eficiencia en todos los edificios. Solo entonces podrás construir la Estación Espacial y lograr la victoria total.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="row mb-5">
            <div class="col-12">
                <h3 class="text-center fw-bold mb-4" style="color: #4facfe;">📊 Características del Jugador</h3>
                <div class="row g-3">
                    <div class="col-md-3 col-sm-6">
                        <div class="card text-center shadow border-0 h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <div class="card-body">
                                <div class="fs-1 mb-2">🎲</div>
                                <h6 class="text-white fw-bold">Suerte</h6>
                                <p class="text-white small mb-0">Aumenta la probabilidad de encontrar recursos</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="card text-center shadow border-0 h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <div class="card-body">
                                <div class="fs-1 mb-2">❤️</div>
                                <h6 class="text-white fw-bold">Vitalidad</h6>
                                <p class="text-white small mb-0">Reduce el tiempo necesario para construir edificios</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="card text-center shadow border-0 h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <div class="card-body">
                                <div class="fs-1 mb-2">💡</div>
                                <h6 class="text-white fw-bold">Ingenio</h6>
                                <p class="text-white small mb-0">Reduce el tiempo necesario para inventar</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="card text-center shadow border-0 h-100" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                            <div class="card-body">
                                <div class="fs-1 mb-2">⚡</div>
                                <h6 class="text-white fw-bold">Velocidad</h6>
                                <p class="text-white small mb-0">Reduce el tiempo necesario para desplazarse entre zonas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection