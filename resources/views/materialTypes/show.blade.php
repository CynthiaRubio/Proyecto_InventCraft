@extends('layouts.full')

@section('title', 'Materiales tipo {{$materialType->name}}')

@section('content')
<h2 class="text-center mb-4">{{$materialType->name}}</h2>
    <div class="container mt-5">

        <div class="table-responsive mb-4">
            <table class="table table-bordered text-center">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">Nombre</th>
                        <th class="text-center">Características del material</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($materials as $material)
                    <tr>
                        <td>{{$material->name}}</td>
                        <td><a href="{{ route ('materials.show' , $material->id)}}" class="btn btn-warning">Ver material</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

        
@endsection
