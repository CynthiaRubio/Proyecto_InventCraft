

@extends('layouts.full')

@section('title', 'Mi Inventario')

@section('content')

<div class="container mt-5">
    <h2 class="text-center mb-4 fw-bold"> 📋 Inventario de {{$user->name}} 📋 </h2>

    <div class="row">
        <div class="col-md-12">
            <h3 class="d-flex justify-content-center align-items-center btn btn-light w-100 fw-bold fs-4">
                🛠️ Inventos 🛠️
            </h3>

            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col" class="text-center">Nombre del Invento</th>
                            <th scope="col" class="text-center">Detalles</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inventions as $invention)
                            <tr>
                                <td class="fw-bold text-dark text-center">{{ $invention->name }}</td>
                                <td class="text-center">
                                    <a href="{{ route('inventions.show', $invention->_id) }}" class="btn btn-warning btn-sm">
                                        Ver Invento
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-danger">No hay inventos disponibles.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection