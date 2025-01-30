@extends('layouts.full')

@section('title', 'Mi Perfil')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card text-light border-0 rounded" style="background: url('{{ asset('images/profile-bg.webp') }}') center/cover no-repeat;">
                <div class="card-body p-4 d-flex align-items-center" style="background: rgba(0,0,0,0.6); border-radius: 10px;">
                    
                    <!-- Sección Izquierda: Foto, Nombre y Correo -->
                    <div class="text-center me-4" style="width: 30%;">
                        <img src="{{ asset('images/avatars/'.$user->avatar.'.webp') }}" alt="Perfil de {{ $user->name }}" 
                             class="rounded-circle border border-3 border-light mb-3" width="120">
                        
                        <h2 class="fw-bold">{{ $user->name }}</h2>
                        <p class="fw-bold">{{ $user->email }}</p>
                    </div>

                    <!-- Sección Derecha: Información del Jugador -->
                    <div class="flex-grow-1">
                        <!-- Puntos del jugador -->
                        @if($user->unasigned_points > 0)
                            <div class="badge bg-secondary fs-5 mb-3"><a href="{{route('users.points',$user)}}" class="btn btn-primary">Puntos del Jugador</a></div>
                        @endif
                        <!-- Barra de Nivel -->
                        <div class="mb-3">
                            <h6 class="text-light">Nivel {{ $user->level }}</h6>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-primary" role="progressbar" 
                                     style="width: {{ $user->level % 100 }}%;" 
                                     aria-valuenow="{{ $user->level % 100 }}" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>

                        <!-- Barra de Experiencia -->
                        <div>
                            <h6 class="text-light">Experiencia: {{ $user->experience }} EXP</h6>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-warning" role="progressbar" 
                                     style="width: {{ $user->experience % 100 }}%;" 
                                     aria-valuenow="{{ $user->experience % 100 }}" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Sección de Estadísticas -->
            <div class="mt-4">
                <h4 class="text-center text-dark">📊 Mis Estadísticas 📊</h4>
                <ul class="list-group list-group-flush">
                    @if ($user->stats->isEmpty())
                        <li class="list-group-item text-center text-danger">No hay estadísticas disponibles.</li>
                    @else
                        @foreach ($user->stats as $stat)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="fw-bold">{{ ucfirst($stat->stat->name) }}</span>
                                <span class="badge bg-primary fs-5">{{ $stat->value }}</span>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>

            <!-- Botón de regreso -->
            <div class="text-center mt-4">
                @if($zone !== null)
                <a href="{{ route('zones.show', $zone->_id) }}" class="btn btn-lg btn-warning shadow fw-bold">Ir a la zona {{$zone->name}}</a>
                @else
                <a href="{{ route('zones.index') }}" class="btn btn-lg btn-warning shadow fw-bold">Ir al mapa</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
