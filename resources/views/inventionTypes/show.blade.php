@extends('layouts.full')

@section('title', "Tipo de Invento {$invention_type->name}")

@section('content')
    @php
        $pageTitle = "Tipo de Invento " . $invention_type->name;
    @endphp
    <x-page-title 
        :title="$pageTitle" 
        gradient="linear-gradient(135deg, #FF9800 0%, #FFC107 100%)"
        borderColor="#FF9800"
    />

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-9">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Zona:</strong>
                            <div class="mt-1">
                                <a href="{{ route('zones.show', $invention_type->zone->id) }}" style="color: #007bff; font-weight: 600; text-decoration: none;">
                                    {{ $invention_type->zone->name }}
                                </a>
                            </div>
                        </div>

                        <div class="mb-3">
                            <strong>Edificio:</strong>
                            <div class="mt-1">
                                @if($invention_type->building)
                                    <a href="{{ route('buildings.show', $invention_type->building->id) }}" style="color: #007bff; font-weight: 600; text-decoration: none;">
                                        {{ $invention_type->building->name }}
                                    </a>
                                @else
                                    Ninguno
                                @endif
                            </div>
                        </div>

                        @if($invention_type->inventionTypesNeed->isNotEmpty())
                            <div class="mb-3">
                                <strong>Puedes crear a partir de {{ $invention_type->name }} Inventos tipo:</strong>
                                <ul class="mt-1 mb-0 list-unstyled">
                                    @foreach($invention_type->inventionTypesNeed as $invention)
                                        <li>
                                            <a href="{{ route('inventionTypes.show', $invention->inventionType->id) }}" style="color: #007bff; font-weight: 600; text-decoration: none;">
                                                {{ $invention->inventionType->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if($invention_type->inventionTypes->isNotEmpty())
                            <div class="mb-3">
                                <strong>Inventos que necesitas para crear este tipo de invento:</strong>
                                <ul class="mt-1 mb-0 list-unstyled">
                                    @foreach($invention_type->inventionTypes as $invention)
                                        <li>
                                            <a href="{{ route('inventionTypes.show', $invention->inventionTypeNeed->id) }}" style="color: #007bff; font-weight: 600; text-decoration: none;">
                                                {{ $invention->inventionTypeNeed->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-lg-3 text-center mb-4 mb-md-0">
                <img src="{{ asset('images/inventionTypes/' . $invention_type->name . '.png') }}" 
                     alt="{{ $invention_type->name }}" 
                     class="img-fluid" 
                     style="max-width: 200px; height: auto;">
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12 text-center d-flex gap-2 justify-content-center flex-wrap">
                <x-action-button 
                    :href="route('inventories.show', $invention_type)" 
                    text="Ver inventos de este tipo" 
                    variant="outline-warning"
                    size="sm"
                />
                <x-action-button 
                    :href="route('createInvention', $invention_type)" 
                    text="Crear un invento de este tipo" 
                    variant="warning"
                    size="sm"
                />
                <x-action-button 
                    :href="route('inventionTypes.index')" 
                    text="Volver al listado" 
                    variant="outline-secondary"
                    size="sm"
                />
            </div>
        </div>
    </div>
@endsection
