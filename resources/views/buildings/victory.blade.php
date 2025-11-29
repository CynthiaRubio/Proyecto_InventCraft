@extends('layouts.full')

@section('title', '¡VICTORIA!')

@section('content')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Mensaje de Victoria -->
            <div class="text-center mb-5">
                <div class="victory-container p-5 rounded-4 shadow-lg" 
                     style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
                            border: 5px solid #ffd700; 
                            animation: victoryPulse 2s ease-in-out infinite;">
                    <h1 class="display-1 fw-bold text-white mb-4" style="text-shadow: 3px 3px 6px rgba(0,0,0,0.5);">
                        🏆 ¡VICTORIA! 🏆
                    </h1>
                    <h2 class="display-4 fw-bold text-warning mb-4" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
                        {{ $user->name }}
                    </h2>
                    <p class="lead text-white fs-3 mb-4">
                        Has construido la <strong class="text-warning">Estación Espacial</strong> y completado el juego.
                    </p>
                    <p class="text-white fs-4 mb-4">
                        ¡Felicidades por alcanzar la máxima eficiencia en todos los edificios!
                    </p>
                </div>
            </div>

            <!-- Estadísticas del Jugador -->
            <div class="row mb-5">
                <div class="col-md-4 mb-4">
                    <div class="card text-center shadow-lg border-0" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <div class="card-body">
                            <h3 class="text-white fw-bold">Nivel</h3>
                            <h2 class="display-3 text-white fw-bold">{{ $user->level }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card text-center shadow-lg border-0" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                        <div class="card-body">
                            <h3 class="text-white fw-bold">Experiencia</h3>
                            <h2 class="display-3 text-white fw-bold">{{ $user->experience }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card text-center shadow-lg border-0" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                        <div class="card-body">
                            <h3 class="text-white fw-bold">Edificios</h3>
                            <h2 class="display-3 text-white fw-bold">100%</h2>
                            <p class="text-white mb-0">Eficiencia Máxima</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Imagen de la Estación Espacial -->
            <div class="text-center mb-5">
                <img src="{{ asset('images/buildings/Estación Espacial.webp') }}" 
                     alt="Estación Espacial" 
                     class="img-fluid rounded shadow-lg" 
                     style="max-width: 500px; animation: float 3s ease-in-out infinite;">
            </div>

            <!-- Botones de Acción -->
            <div class="text-center">
                <a href="{{ route('users.show') }}" class="btn btn-primary btn-lg me-3 shadow">
                    Ver Mi Perfil
                </a>
                <a href="{{ route('buildings.index') }}" class="btn btn-success btn-lg me-3 shadow">
                    Ver Edificios
                </a>
                <a href="{{ route('users.ranking') }}" class="btn btn-warning btn-lg shadow">
                    Ver Ranking
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

