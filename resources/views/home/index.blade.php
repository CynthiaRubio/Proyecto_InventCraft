@extends('layouts.full')

@section('title', 'Inicio')

@section('content')
    <div class="d-flex flex-column align-items-center justify-content-center min-vh-100">
        <!-- Logo -->
        <img src="{{ asset('images/logo.png') }}" alt="Logo del Juego" class="img-fluid" style="max-width: 300px;">

        <!-- Mensaje de Bienvenida -->
        <h1 class="mt-4 text-center">¡Bienvenido a InventCraft!</h1>
        <br>
        <a href="{{ route('initial_register') }}" class="btn btn-primary">Regístrate</a>
        <br>
        <a href="{{ route('initial_login') }}" class="btn btn-primary">Inicia sesión</a>
        <br>
        <a href="{{ route('logout') }}" class="btn btn-primary">Cierra sesión</a>
    </div>
@endsection