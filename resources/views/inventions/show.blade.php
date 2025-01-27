@extends('layouts.basic')

@section('title', "Invento tipo $invention_type")

@section('content')
<h2 class="text-center mb-4">{{ $invention->name }}</h2>
    <div class="container mt-5">

        <div class="row align-items-center">

            <div class="col-md-6">
                <ul>
                    <dl>
                        <dt>Nombre del invento:</dt>
                        <dd>{{$invention->name}}</dd>
                        <dt>Invento tipo:</dt>
                        <dd>{{$invention_type}}</dd>
                        <dt>Material con el que se ha hecho:</dt>
                        <dd>{{$material}}</dd>
                        <dt>Eficiencia del invento:</dt>
                        <dd>{{$invention->efficiency}}</dd>
                    </dl>
                </ul>
                    
                <div class="text-center">
                    <!-- TO DO revisar los enlaces -->
                @if(isset($_SERVER['HTTP_REFERER']))
                    <a href="{{$_SERVER['HTTP_REFERER']}}" class="btn btn-warning">Volver al listado de inventos</a>
                @else
                    <a href="{{ route('inventions.index')}}" class="btn btn-warning">Volver al listado de inventos</a>
                @endif
                </div>
                
            </div>
        </div>
        
    </div>
@endsection