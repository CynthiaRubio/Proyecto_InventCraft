@extends('layouts.full')

@section('title', "Materiales tipo $materialType->name")

@section('content')

<h2 class="text-center mb-4 fw-bold p-3 rounded-3" style="background-color: #2196F3; color: white; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);">
    {{$materialType->name}}
</h2>

<div class="container mt-5">
    <div class="row">
       
        <div class="col-md-4 mb-4">
            <img src="{{ asset('images/materialTypes/' . $materialType->name . '.png') }}" alt="{{ $materialType->name }}" class="img-fluid mb-3" style="width: 250px; height: auto;">
        </div>
        
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="card-header bg-info text-white text-center">Nombre</th>
                                    <th class="card-header bg-info text-white text-center">Características del material</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($materialType->materials as $material)
                                <tr>
                                    <td class="text-center">{{ $material->name }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('materials.show', $material->id) }}" class="btn btn-outline-primary btn-sm">
                                            Ver material
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <a href="{{ route('materialTypes.index') }}" class="btn btn-outline-dark btn-sm">
                    Volver atrás
                </a>
            </div>
        </div>
    </div>
</div>


        
@endsection
