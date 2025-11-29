@extends('layouts.full')

@section('title', "Invento tipo {$invention->inventionType->name}")

@section('content')
    <x-page-title 
        title="{{ $invention->name }}" 
        gradient="linear-gradient(135deg, #FF9800 0%, #FFC107 100%)"
        borderColor="#FFC107"
    />

    <div class="container d-flex align-items-center justify-content-center" style="min-height: calc(100vh - 350px);">
        <div class="w-100">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">
                    <div class="row align-items-center">
                        <div class="col-md-8 col-lg-9">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    @if($invention->inventionType->description)
                                        <div class="mb-3">
                                            <dt class="fw-bold">Descripción:</dt>
                                            <dd class="mt-1">{{ $invention->inventionType->description }}</dd>
                                        </div>
                                    @endif

                                    <div class="mb-3">
                                        <dt class="fw-bold">Tipo de invento:</dt>
                                        <dd class="mt-1">
                                            <a href="{{ route('inventionTypes.show', $invention->inventionType->id) }}" style="color: #007bff; font-weight: 600; text-decoration: none;">
                                                {{ $invention->inventionType->name }}
                                            </a>
                                        </dd>
                                    </div>

                                    <div class="mb-3">
                                        <dt class="fw-bold">Material:</dt>
                                        <dd class="mt-1">{{ $invention->material->name }}</dd>
                                    </div>

                                    <div class="mb-3">
                                        <dt class="fw-bold">Eficiencia:</dt>
                                        <dd class="mt-1">{{ number_format($invention->efficiency, 1) }}%</dd>
                                    </div>

                                    <div class="mb-3">
                                        <dt class="fw-bold">Edificio que puedes construir:</dt>
                                        <dd class="mt-1">
                                            @if($invention->inventionType->building)
                                                <a href="{{ route('buildings.show', $invention->inventionType->building->id) }}" style="color: #007bff; font-weight: 600; text-decoration: none;">
                                                    {{ $invention->inventionType->building->name }}
                                                </a>
                                            @else
                                                Ninguno
                                            @endif
                                        </dd>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-lg-3 text-center mb-4 mb-md-0">
                            <img src="{{ asset('images/inventionTypes/' . $invention->inventionType->name . '.png') }}" 
                                 alt="{{ $invention->inventionType->name }}" 
                                 class="img-fluid" 
                                 style="max-width: 200px; height: auto;">
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            <x-action-button 
                                :href="route('inventionTypes.index')" 
                                text="Volver al listado de Tipos de Inventos" 
                                variant="outline-warning"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection