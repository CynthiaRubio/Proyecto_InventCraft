

@extends('layouts.full')

@section('title', 'Mi Inventario')

@section('content')
<div class="container mt-5">
<h2 class="text-center mb-4 fw-bold"> 📋 Inventario de {{$inventory->user->name}} 📋 </h2>

<div class="row">
    <div class="col-md-6">
        <h3 class="d-flex justify-content-center align-items-center btn btn-light w-100 fw-bold fs-4">🛠️ Inventos</h3>
        <div class="alert alert-info text-center fw-bold">
        🛠️ Inventos Totales: <span class="text-dark">{{ $total_inventions }}</span>
        </div>
        <div class="accordion">
            @forelse($inventionsByType as $type => $inventions)
                <div class="accordion-item border-light">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed bg-light text-dark fw-bold" type="button" data-bs-toggle="collapse" data-bs-target=".collapseInvention{{ $loop->index }}">
                            <strong>{{ $type }}</strong>  ({{ $inventions->count() }})
                        </button>
                    </h2>
                    <div class="accordion-collapse collapse collapseInvention{{ $loop->index }}">
                        <div class="accordion-body">
                            <ul class="list-group">
                                @foreach($inventions as $invention)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span class="fw-bold text-dark">{{ $invention->name }}</span>
                                        <a href="{{ route('inventions.show', $invention->_id) }}" class="btn btn-warning btn-sm">Ver Invento</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-danger">No hay inventos disponibles.</p>
            @endforelse
        </div>
    </div>

    <div class="col-md-6">
        <h3 class="d-flex justify-content-center align-items-center fs-4 alert alert-info text-center fw-bold">🪵 Materiales</h3>
        <div class="alert alert-info text-center fw-bold">
            🪵 Materiales Totales: <span class="text-dark">{{ $total_materials }}</span>
        </div>
        <div class="accordion">
            @forelse($materialsByType as $type => $materials)
                <div class="accordion-item border-light">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed bg-light text-dark fw-bold" type="button" data-bs-toggle="collapse" data-bs-target=".collapseMaterial{{ $loop->index }}">
                            <strong>{{ $type }}</strong>
                        </button>
                    </h2>
                    <div class="accordion-collapse collapse collapseMaterial{{ $loop->index }}">
                        <div class="accordion-body">
                            <ul class="list-group">
                                @foreach($materials as $material)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span class="fw-bold text-dark">{{ $material->material->name }}: Cantidad ({{ $material->quantity }})</span>
                                        <a href="{{ route('materials.show', $material->material) }}" class="btn btn-warning btn-sm">Ver Material</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-danger">No hay materiales disponibles.</p>
            @endforelse
        </div>
    </div>
</div>


@endsection