@extends('layouts.basic')

@section('title', 'Registro')

@section('content')
<div class="container mt-5">
    
    <div class="row align-items-center">
    <div class="col-md-6 text-center">
    <!-- <div class="d-flex flex-column align-items-center justify-content-center min-vh-100"> -->
        <!-- Logo -->
        <img src="{{ asset('images/home/logo.png') }}" alt="Logo del Juego" class="img-fluid" style="max-width: 300px;">
    
        <form action="{{ route('form.register') }}" method="post">
            @csrf
            
            <div class="form-group">
                <label for="name">Nombre:</label>
                <input type="text" class="form-control" id="name" name="name">
                @if($errors->has('name'))
                    <span class="text-danger">{{$errors->first('name') }}</span>
                @endif
            </div> 
            <div class="form-group">
                <label for="email">Correo electrónico:</label>
                <input type="email" class="form-control" id="email" name="email">
                @if($errors->has('email'))
                    <span class="text-danger">{{$errors->first('email') }}</span>
                @endif
            </div> 
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" class="form-control" id="password" name="password">
                @if($errors->has('name'))
                    <span class="text-danger">{{$errors->first('name') }}</span>
                @endif
            </div> 
            <div class="form-group">
                <label for="password_confirmation">Confirma la contraseña:</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
            </div> 
            <br>
            <div class="form-group text-center">
                <button type="submit" class="btn btn-success">Regístrate</button>
            </div>
            <br>
        </form>
        
    </div>
    <div class="col-md-6 text-center">
        <img src= "{{asset('images/home/principal.png')}}" alt="Foto principal del juego" class="img-fluid">
        <div class="text-center">
            <p>Si ya estás registrado <a href="{{ route('login') }}" class="btn btn-primary">Inicia Sesión</a></p>
        </div>
    </div>
</div>
</div>
@endsection