@extends('layouts.full')

@section('title', 'Tipos de Materiales')

@section('content')
    <x-page-title 
        title="Tipos de Materiales" 
        gradient="linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)"
        borderColor="#4facfe"
    />

    <div class="container">
        <div class="row g-4">
            @foreach($materialTypes as $type)
                <div class="col-12 col-md-6 col-lg-4">
                    <a href="{{ route('materialTypes.show', $type->id) }}" class="text-decoration-none">
                        <div class="card shadow-sm h-100">
                            <div class="card-body text-center">
                                <img src="{{ asset('images/materialTypes/' . $type->name . '.png') }}" 
                                     alt="{{ $type->name }}" 
                                     class="img-fluid mb-3" 
                                     style="max-width: 200px; height: auto;">
                                <h5 class="card-title fw-bold text-primary">{{ $type->name }}</h5>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
