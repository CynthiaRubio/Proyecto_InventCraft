@extends('layouts.full')

@section('title', 'Login')

@section('content')
    <div class="d-flex flex-column align-items-center justify-content-center min-vh-100">
        <!-- Logo -->
        <img src="{{ asset('images/logo.png') }}" alt="Logo del Juego" class="img-fluid" style="max-width: 300px;">
    
        <form action="{{ route('login') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="email">Correo electrónico:</label>
                <input type="email" class="form-control" id="email" name="email">
            </div> 
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" class="form-control" id="password" name="password">
            </div> 
            <br>
            <div class="form-group text-center">
                <button type="submit" class="btn btn-success">Iniciar el juego</button>
            </div>
            <br>
            <div class="form-group text-center">
                <a href="{{ route('register') }}" class="btn btn-secondary">Registrarse</a>
            </div>
        </form>
    </div>
@endsection