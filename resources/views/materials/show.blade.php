@extends('layouts.basic')

@section('title', "material->name")

@section('content')
<h2 class="text-center mb-4">{{$material->name}}</h2>
    <div class="container mt-5">

        <div class="row align-items-center">

            <div class="col-md-6">
                <ul>
                    <dl>
                        <dt>Nombre:</dt>
                        <dd>{{$material->name}}</dd>
                        <dt>Descripción:</dt>
                        <dd>{{$material->description}}</dd>
                        <dt>Eficiencia</dt>
                        <dd>{{$material->efficiency}}</dd>
                    </dl>
                </ul>

                <div class="text-center">
                @if(isset($_SERVER['HTTP_REFERER']))
                    <a href="{{$_SERVER['HTTP_REFERER']}}"><button type="submit" value="volver" id="volver" class="btn btn-warning">Volver al listado de materiales</button></a>
                @else
                    <a href="{{ route('materials.index')}}"><button type="submit" value="volver" id="volver" class="btn btn-warning">Volver al listado de materiales</button></a>
                @endif
                </div>
                
            </div>
        </div>
    </div>
@endsection