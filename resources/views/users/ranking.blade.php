@extends('layouts.full')

@section('title', 'Ranking de Usuarios')

@section('content')
    <x-page-title 
        title="🏆 Ranking de Jugadores 🏆" 
        gradient="linear-gradient(to right, #FFD700, #FFA500)"
        borderColor="#FFD700"
    />

    <div class="container mt-5">
        <div class="table-responsive">
            <table class="table table-striped table-hover shadow-sm">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center">Posición</th>
                        <th>Nombre</th>
                        <th class="text-center">Nivel</th>
                        <th class="text-center">Experiencia</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
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
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No hay usuarios en el ranking</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
