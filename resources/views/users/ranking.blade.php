@extends('layouts.full')

@section('title', 'Ranking de Usuarios')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4 fw-bold">🏆 Ranking de Jugadores 🏆</h2>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th class="text-center">Posición</th>
                    <th>Nombre</th>
                    <th class="text-center">Nivel</th>
                    <th class="text-center">Experiencia</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $index => $user)
                <tr>
                    <td class="text-center fw-bold">
                        {{ $index + 1 }}
                        @if($index == 0)
                            🥇
                        @elseif($index == 1)
                            🥈
                        @elseif($index == 2)
                            🥉
                        @endif
                    </td>
                    <td>{{ $user->name }}</td>
                    <td class="text-center fw-bold">{{ $user->level }}</td>
                    <td class="text-center">{{ $user->experience }} XP</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
