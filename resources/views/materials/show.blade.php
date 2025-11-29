@extends('layouts.full')

@section('title', $material->name)

@section('content')
    <div class="container mt-5">
        <x-page-title 
            title="{{ $material->name }}" 
            gradient="linear-gradient(135deg, #FF9800 0%, #FFC107 100%)"
            borderColor="#FF9800"
        />
    </div>

    <div class="container d-flex align-items-center justify-content-center" style="min-height: calc(100vh - 350px);">
        <div class="w-100">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">
                    <div class="row align-items-center">
                        <div class="col-md-8 col-lg-9">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <strong>Tipo de material:</strong>
                                        <div class="mt-1">{{ $material->materialType->name }}</div>
                                    </div>

                                    <div class="mb-3">
                                        <strong>Descripción:</strong>
                                        <div class="mt-1">{{ $material->description }}</div>
                                    </div>

                                    <div class="mb-3">
                                        <strong>Eficiencia:</strong>
                                        <div class="mt-1">{{ $material->efficiency }}%</div>
                                    </div>

                                    <div class="mb-3">
                                        <strong>Lo puedes encontrar en la zona:</strong>
                                        <div class="mt-1">
                                            <a href="{{ route('zones.show', $material->zone->id) }}" style="color: #007bff; font-weight: 600; text-decoration: none;">
                                                {{ $material->zone->name }}
                                            </a>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <strong>Te servirá para crear inventos tipo:</strong>
                                        <ul class="mt-1 mb-0">
                                            @foreach($material->materialType->inventionTypes as $inventionType)
                                                <li>
                                                    <a href="{{ route('inventionTypes.show', $inventionType->id) }}" style="color: #007bff; font-weight: 600; text-decoration: none;">
                                                        {{ $inventionType->name }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-lg-3 text-center mb-4 mb-md-0">
                            <img src="{{ asset('images/materialTypes/' . $material->materialType->name . '.png') }}" 
                                 alt="{{ $material->materialType->name }}" 
                                 class="img-fluid" 
                                 style="max-width: 200px; height: auto;">
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex gap-2 justify-content-center flex-wrap">
                                <x-action-button 
                                    :href="route('inventories.index')" 
                                    text="Volver al Inventario" 
                                    variant="outline-primary"
                                    size="sm"
                                />
                                <x-action-button 
                                    :href="route('materialTypes.show', $material->materialType->id)" 
                                    text="Ver Tipo de Material" 
                                    variant="outline-info"
                                    size="sm"
                                />
                                <x-action-button 
                                    :href="route('zones.show', $material->zone->id)" 
                                    text="Ver Zona" 
                                    variant="outline-success"
                                    size="sm"
                                />
                                <x-action-button 
                                    :href="route('materialTypes.index')" 
                                    text="Listado de Materiales" 
                                    variant="outline-warning"
                                    size="sm"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection