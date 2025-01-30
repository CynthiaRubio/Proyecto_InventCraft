
@extends('layouts.full') <!-- Hereda de la plantilla de layouts app -->

@section('title', 'Tipos de Materiales') <!-- Le pasamos el titulo a la plantilla -->

@section('content') <!-- Le pasamos el contenido a la plantilla -->

    <h2 class="text-center">Tipos de Materiales</h2>

    <div class="accordion" id="materialsAccordion" style="max-width: 600px; margin: 0 auto;">
        @foreach($materialTypes as $type)
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading{{ $type->id }}">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $type->id }}" aria-expanded="true" aria-controls="collapse{{ $type->id }}">
                        {{ $type->name }}
                    </button>
                </h2>
                <div id="collapse{{ $type->id }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $type->id }}" data-bs-parent="#materialsAccordion">
                    <div class="accordion-body text-center">
                        <img src="{{ asset('images/materialTypes/' . $type->name . '.png') }}" alt="{{ $type->name }}" class="img-fluid mb-3" style="width: 250px; height: auto;">
                    </div>
                    <div class="accordion-body text-center">
                        <a href="{{ route('materialTypes.show' , $type->id) }}" class="btn btn-warning mt-3">Ver todos los materiales tipo {{ $type->name }}</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@endsection
