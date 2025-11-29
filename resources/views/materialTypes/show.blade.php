@extends('layouts.full')

@section('title', "Materiales tipo $materialType->name")

@section('content')
    <div class="container mt-5">
        <x-page-title 
            title="{{ $materialType->name }}" 
            gradient="linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)"
            borderColor="#4facfe"
        />

        <div class="row justify-content-center mt-4">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="text-center mb-4">
                    <img src="{{ asset('images/materialTypes/' . $materialType->name . '.png') }}" 
                         alt="{{ $materialType->name }}" 
                         class="img-fluid" 
                         style="max-width: 300px; height: auto;">
                </div>
                
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover material-types-table">
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
                                                Ver
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-5 mb-5">
                    <x-action-button 
                        :href="route('materialTypes.index')" 
                        text="Volver atrás" 
                        variant="outline-info"
                    />
                </div>
            </div>
        </div>
    </div>
@endsection
