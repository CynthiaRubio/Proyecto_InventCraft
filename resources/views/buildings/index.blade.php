@extends('layouts.full')

@section('title', 'Edificios')

@section('content')
    <x-page-title 
        title="Edificios" 
        gradient="linear-gradient(to right, #3498db, #2980b9)"
        borderColor="#3498db"
        size="3rem"
    />

    <div class="container">
        <div class="row g-3">
        @foreach($buildings as $building)
            @php
                $isSpaceStation = $building->name === 'Estación Espacial';
                $canBuild = $isSpaceStation ? ($canBuildSpaceStation['can_build'] ?? false) : true;
            @endphp
            <div class="col-12 col-md-4">
                <div class="{{ !$canBuild ? 'opacity-50' : '' }}" style="{{ !$canBuild ? 'pointer-events: none;' : '' }}">
                    <x-image-card 
                        :imagePath="asset('images/buildings/' . $building->name . '.webp')"
                        :alt="'Imagen de ' . $building->name"
                        :title="$building->name"
                        :description="$building->description"
                    >
                        @if(!$canBuild)
                            <div class="alert alert-warning mt-3 mb-0">
                                <small>⚠️ Requisitos no cumplidos</small>
                            </div>
                        @endif
                        <div class="text-center mt-3">
                            @if($canBuild)
                                <x-action-button 
                                    :href="route('buildings.show', $building->id)" 
                                    text="Ver" 
                                    variant="outline-primary"
                                />
                            @else
                                <button class="btn btn-outline-secondary" disabled>
                                    No disponible
                                </button>
                            @endif
                        </div>
                    </x-image-card>
                </div>
            </div>
        @endforeach
        </div>
    </div>
@endsection

