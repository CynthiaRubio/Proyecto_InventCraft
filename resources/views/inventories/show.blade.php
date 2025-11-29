@extends('layouts.full')

@section('title', 'Mi Inventario')

@section('content')
    <x-page-title 
        :title="'📋 Inventario de ' . $viewModel->userName() . ' 📋'" 
        gradient="linear-gradient(to right, #2196F3, #1976D2)"
        borderColor="#2196F3"
    />

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                @if($viewModel->inventions->count() > 0)
                    <x-section-header 
                        :title="'🛠️ Inventos Tipo ' . $viewModel->inventions->first()->inventionType->name . ' 🛠️'"
                        bgColor="#2196F3"
                        size="1.5rem"
                    />

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover shadow-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col" class="text-center">Nombre del Invento</th>
                                    <th scope="col" class="text-center">Detalles</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($viewModel->inventions as $invention)
                                    <tr>
                                        <td class="fw-bold text-dark text-center">{{ $invention->name }}</td>
                                        <td class="text-center">
                                            <x-action-button 
                                                :href="route('inventions.show', $invention->id)" 
                                                text="Ver" 
                                                variant="warning"
                                                size="sm"
                                            />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info text-center">
                        <p class="mb-0">No hay inventos disponibles de este tipo.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection