@extends('layouts.basic')

@section('title', 'Registro - InventCraft')

@section('content')
<div class="container-fluid px-0">
    <!-- Hero Section con colores azul -->
    <div class="hero-section text-center text-white py-5" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); min-height: 50vh; display: flex; align-items: center; justify-content: center; position: relative;">
        <div class="container" style="position: relative; z-index: 1;">
            <img src="{{ asset('images/home/logo.png') }}" alt="Logo del Juego" class="img-fluid mb-4" style="max-width: 250px; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.3)); pointer-events: none; user-select: none;">
            <h1 class="display-3 fw-bold mb-3" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5); pointer-events: none; user-select: none;">¡Únete a InventCraft!</h1>
            <p class="lead fs-4 mb-4" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.5); pointer-events: none; user-select: none;">Crea tu cuenta y comienza tu aventura</p>
        </div>
    </div>

    <!-- Formulario de registro con imagen lateral -->
    <div class="container py-5">
        <div class="row align-items-center">
            <!-- Imagen lateral -->
            <div class="col-12 col-lg-6 d-none d-lg-block text-center mb-4 mb-lg-0">
                <img src="{{ asset('images/home/principal.png') }}" alt="Foto principal del juego" class="img-fluid rounded shadow-lg" style="max-height: 600px; object-fit: contain;">
            </div>
            
            <!-- Formulario de registro -->
            <div class="col-12 col-lg-6">
                <div class="card shadow-lg border-0" style="border-top: 4px solid #4facfe !important;">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-4 fw-bold" style="color: #4facfe;">Crear Cuenta</h2>
                        
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('form.register') }}" method="post">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold" style="color: #4facfe;">Nombre:</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required autofocus style="border-color: #4facfe;">
                            </div> 
                            
                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold" style="color: #4facfe;">Correo electrónico:</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required style="border-color: #4facfe;">
                            </div> 
                            
                            <div class="mb-3">
                                <label for="password" class="form-label fw-bold" style="color: #4facfe;">Contraseña:</label>
                                <input type="password" class="form-control" id="password" name="password" required style="border-color: #4facfe;">
                            </div> 
                            
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label fw-bold" style="color: #4facfe;">Confirma la contraseña:</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required style="border-color: #4facfe;">
                            </div> 
                            
                            <div class="d-grid gap-2 mb-3">
                                <button type="submit" class="btn btn-lg fw-bold text-white" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border: none;">Regístrate</button>
                            </div>
                            
                            <div class="text-center">
                                <p class="mb-0">¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text-decoration-none fw-bold" style="color: #4facfe;">Inicia Sesión aquí</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection