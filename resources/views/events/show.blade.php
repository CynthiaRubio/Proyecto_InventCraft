@extends('layouts.full')

@section('title', 'Detalles del Evento')

@section('content')
    <x-page-title 
        :title="'📌 ' . $event->name" 
        gradient="linear-gradient(135deg, #4CAF50 0%, #81C784 100%)"
        borderColor="#4CAF50"
    />

    <div class="container d-flex align-items-center justify-content-center" style="min-height: calc(100vh - 350px);">
        <div class="w-100">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="mb-3">
                                <dt class="fw-bold">Descripción:</dt>
                                <dd class="mt-1">{{ $event->description }}</dd>
                            </div>

                            <div class="mb-3">
                                <dt class="fw-bold">Zona:</dt>
                                <dd class="mt-1">{{ $event->zone->name }}</dd>
                            </div>

                            <div class="mb-3">
                                <dt class="fw-bold">Pérdida de Recursos:</dt>
                                <dd class="mt-1">{{ number_format($event->loss_percent, 2) }}%</dd>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            <div class="d-flex gap-2 justify-content-center flex-wrap">
                                <x-action-button 
                                    :href="route('events.index')" 
                                    text="Volver" 
                                    variant="outline-secondary"
                                />
                                <x-action-button 
                                    :href="route('events.edit', $event->id)" 
                                    text="Editar" 
                                    variant="outline-warning"
                                />
                                <form action="{{ route('events.destroy', $event->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-lg shadow fw-bold" onclick="return confirm('¿Eliminar evento?')">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
