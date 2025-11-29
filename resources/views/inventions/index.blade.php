@extends('layouts.full')

@section('title', 'Inventos')

@section('content')
    <x-page-title 
        title="Mis Inventos" 
        gradient="linear-gradient(to right, #FF9800, #FFC107)"
        borderColor="#FFC107"
    />

    <div class="container">
        <div class="table-responsive">
            <table class="table table-bordered table-striped shadow-sm" style="max-width: 60%; margin: 0 auto;">
                <thead class="table-warning">
                    <tr>
                        <th class="text-center">Nombre del Invento</th>
                        <th class="text-center">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventions as $invention)
                        <tr>
                            <td class="fw-bold">{{ $invention->name }}</td>
                            <td class="text-center">
                                <x-action-button 
                                    :href="route('inventions.show', $invention->id)" 
                                    text="Ver Invento" 
                                    variant="warning"
                                    size="sm"
                                />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted">No hay inventos disponibles</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection