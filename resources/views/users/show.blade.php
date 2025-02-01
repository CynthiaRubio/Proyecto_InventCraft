@extends('layouts.full')

@section('title', 'Mi Perfil')

@section('content')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card text-light border-0 rounded" style="background: url('{{ asset('images/avatars/profile-bg.webp') }}') center/cover no-repeat;">
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
                            <div class="badge fs-5 mb-3">
                                <a href="{{route('users.points',$user)}}" class="btn btn-outline-light fw-bold mt-2">
                                    Puntos del Jugador
                                </a>
                            </div>
                        @endif
                        <!-- Botón para cambiar avatar -->
                        <div class="badge fs-5 mb-3">
                            <a href="{{ route('users.avatar', $user->_id) }}" class="btn btn-outline-light fw-bold mt-2">
                                Cambiar Avatar
                            </a>
                        </div>
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
            <!-- <div class="mt-4">
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
            </div> -->

            <!-- Sección de Estadísticas -->
<!-- <div class="mt-5">
    <h4 class="text-center text-dark">📊 Mis Estadísticas 📊</h4>
    <h4 class="text-center" style="font-size: 2.5rem; font-weight: bold; background: linear-gradient(to right, #6a11cb, #2575fc); -webkit-background-clip: text; color: transparent; text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.5);">
    📊 Mis Estadísticas 📊
</h4>

<h4 class="text-center" style="font-size: 2.5rem; font-weight: bold; color: #3498db; border-bottom: 3px solid #3498db; padding-bottom: 10px;">
    📊 Mis Estadísticas 📊
</h4> -->

<h4 class="text-center text-light mt-5" style="font-size: 2.5rem; font-weight: bold; background-color: #3498db; padding: 10px 20px; border-radius: 5px;">
    📊 Mis Estadísticas 📊
</h4>







    <div class="d-flex justify-content-center flex-wrap">
        @if ($user->stats->isEmpty())
            <div class="alert alert-warning text-center w-100">
                No hay estadísticas disponibles.
            </div>
        @else
            @foreach ($user->stats as $stat)
                <div class="stat-card p-4 m-2 d-flex flex-column align-items-center text-center shadow-lg" style="background: linear-gradient(145deg, #6c63ff, #00bcd4); border-radius: 12px;">
                    <h5 class="card-title text-light fw-bold mb-3">{{ $stat->stat->name }}</h5>
                    <span class="badge rounded-pill bg-light text-dark fs-5 px-4 py-2">{{ $stat->value }}</span>
                </div>
            @endforeach
        @endif
    </div>
</div>

            <!-- Botones -->
            <div class="text-center mt-5">
                @if($zone !== null)
                    <a href="{{ route('zones.show', $zone->_id) }}" class="btn btn-outline-primary btn-lg shadow fw-bold me-2">Ir a la zona {{$zone->name}}</a>
                @else
                    <a href="{{ route('zones.index') }}" class="btn btn-outline-primary btn-lg shadow fw-bold me-2">Ir al mapa</a>
                @endif

                <a href="{{ route('inventories.index') }}" class="btn btn-outline-primary btn-lg shadow fw-bold ms-2">Inventario</a>
            </div>
        </div>
    </div>
</div>


@endsection
