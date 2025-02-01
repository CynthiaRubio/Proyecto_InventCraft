@extends('layouts.full')

@section('title', 'En una acción')

@section('content')

<div class="card shadow-lg p-4 mb-4">
    <div class="card-body text-center">
        <h3 class="text-dark mb-3">⏳ De camino a la zona <span class="text-dark fw-bold">{{ $zone_name }} ⏳</span></h3>
        <p class="fs-5 text-muted">El viaje durará <span class="text-warning fw-bold">{{ $moveTime }} minutos</span>.</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <img src="{{ asset('images/avatars/profile-bg.webp') }}" alt="Imagen de espera entre acciones" class="img-fluid rounded shadow-sm">
    </div>
</div>

<div class="text-center mt-4">
    <a href="{{ route('users.show', auth()->user()->id) }}" class="btn btn-outline-warning btn-lg shadow-sm fw-bold text-warning text-hover-dark">
        Ver mi perfil
    </a>
</div>

@endsection

<style>
    .text-hover-dark:hover {
        color: #000 !important; /* Cambiar el texto a negro al hacer hover */
        background-color: #f39c12 !important; /* Fondo amarillo */
        border-color: #f39c12 !important; /* Borde amarillo */
    }
</style>


