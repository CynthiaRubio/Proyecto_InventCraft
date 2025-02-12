@extends('layouts.basic')

@section('title', 'Login')

@section('content')

    <div class="d-flex flex-column flex-lg-row align-items-center justify-content-center min-vh-100">
    <!-- Imagen lateral -->
    <div class="d-none d-lg-block col-lg-6 p-0">
        <img src="{{ asset('images/home/principal.png') }}" alt="Foto principal del juego" class="img-fluid h-100 w-100 object-cover">
    </div>
    
    <!-- Formulario de login -->
    <div class="col-lg-6 d-flex justify-content-center align-items-center">
        <form action="{{ route('login') }}" method="post" class="w-75">
            @csrf
            <div class="form-group">
                <label for="email" class="form-label">Correo electrónico:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div> 
            <div class="form-group">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div> 
            <br>
            <div class="form-group text-center">
                <button type="submit" class="btn btn-primary w-100">¡A jugar!</button>
            </div>
            <br>
            <div class="form-group text-center">
                <a href="{{ route('register') }}" class="btn btn-primary w-100">Regístrate</a>
            </div>
        </form>
    </div>
    
    <!-- Logo abajo (en móviles o pantallas pequeñas) -->
    <div class="d-flex justify-content-center mt-4">
        <img src="{{ asset('images/home/logo.png') }}" alt="Logo del Juego" class="img-fluid" style="max-width: 200px;">
    </div>
</div>

@endsection