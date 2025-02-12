@extends('layouts.full')

@section('title', 'Invento')

@section('content')

<h2 class="text-center mb-4" style="background: linear-gradient(to right, #FF9800, #FFC107); color: white; padding: 10px; border-radius: 8px;">
    Todos los inventos del juego
</h2>

<table class="table table-bordered table-striped" style="max-width: 60%; margin: 0 auto;">
    <thead class="table-light">
        <tr>
            <th class="text-center">Nombre del Invento</th>
            <th class="text-center">Acción</th>
        </tr>
    </thead>
    <tbody>
        @foreach($inventions as $invention)
            <tr>
                <td>{{ $invention->name }}</td>
                <td class="text-center">
                    <a href="{{ route('inventions.show', $invention->id) }}" class="btn btn-warning btn-sm">Ver Invento</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>


@endsection