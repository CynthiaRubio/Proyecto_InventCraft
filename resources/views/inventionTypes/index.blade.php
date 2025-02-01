
@extends('layouts.full') <!-- Hereda de la plantilla de layouts app -->

@section('title', 'Tipos de Inventos') <!-- Le pasamos el titulo a la plantilla -->

@section('content') <!-- Le pasamos el contenido a la plantilla -->

<h2 class="text-center mb-5 text-black">Tipos de Inventos</h2>

<div class="container mt-5">
    <div class="accordion" id="inventionTypesAccordion" style="max-width: 800px; margin: 0 auto;">
        @foreach($inventionTypes as $index => $type)
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading{{ $index }}">
                    <button class="accordion-button text-center fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="true" aria-controls="collapse{{ $index }}">
                        {{ $type->name }}
                    </button>
                </h2>

                <div id="collapse{{ $index }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $index }}" data-bs-parent="#inventionTypesAccordion">
                    <div class="accordion-body text-center">
                        <img src="{{ asset('images/inventionTypes/' . $type->name . '.png') }}" alt="{{ $type->name }}" class="img-fluid mb-3" style="width: 250px; height: auto;">
                    </div>

                    <div class="accordion-body text-center">
                        <p><strong>Zona en la que puedes encontrarlo:</strong> {{ $type->zone->name }}</p>
                        <p><strong>Tipos de inventos que puedes crear a partir de {{$type->name}}:</strong>
                            <ul class="list-unstyled text-center">
                                @forelse($type->inventionTypes as $invention)
                                    <li>{{ $invention->inventionType->name }}</li>
                                @empty
                                    <li>Ninguno</li>
                                @endforelse
                            </ul>
                        </p>
                       
                        <p><strong>Inventos necesarios para su creación:</strong>
                            <ul class="list-unstyled text-center">
                                @forelse($type->inventionTypesNeed as $invention)
                                    <li>{{ $invention->inventionTypeNeed->name }}</li>
                                @empty
                                    <li>Ninguno</li>
                                @endforelse
                            </ul>
                        </p>
        
                        <p><strong>Edificio que puedes construir:</strong> {{ $type->building->name }}</p>
                    </div>

                    <div class="accordion-body text-center">
                        <a href="{{ route('inventories.show', $type) }}" class="btn btn-warning mt-3">Ver inventos de este tipo</a>
                    </div>

                    <div class="accordion-body text-center">
                        <a href="{{ route('inventions.create.withType', $type) }}" class="btn btn-warning mt-3">Crear un invento de este tipo</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>



@endsection