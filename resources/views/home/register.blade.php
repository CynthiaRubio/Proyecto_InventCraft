@extends('layouts.full')

@section('title', 'Registro')

@section('content')
    <div class="d-flex flex-column align-items-center justify-content-center min-vh-100">
        <!-- Logo -->
        <img src="{{ asset('images/logo.png') }}" alt="Logo del Juego" class="img-fluid" style="max-width: 300px;">
    
        <form action="{{ route('register') }}" method="post">
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
            <div class="form-group text-center">
                <a href="{{ route('index') }}" class="btn btn-secondary">Cancelar registro</a>
            </div>
        </form>
    </div>
@endsection