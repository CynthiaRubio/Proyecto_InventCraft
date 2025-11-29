@extends('layouts.full')

@section('title', 'Asignar Puntos')

@section('content')

<x-page-title 
    title="⭐ Asignar Puntos ⭐" 
    gradient="linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)"
    borderColor="#4facfe"
/>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="card shadow-lg border-0" style="border-top: 4px solid #4facfe !important;">
                <div class="card-body p-4">
                    
                    <!-- Información de puntos disponibles -->
                    <div class="text-center mb-4">
                        <div class="alert alert-info d-inline-block px-4 py-3 mb-0">
                            <h4 class="mb-2 fw-bold">Puntos Disponibles</h4>
                            <p class="fs-3 mb-0">
                                Tienes <strong class="text-primary" id="remaining-points" style="font-size: 2rem;">{{ $user->unasigned_points }}</strong> 
                                <span class="fs-5">puntos por asignar</span>
                            </p>
                        </div>
                    </div>

                    <!-- Formulario -->
                    <form action="{{ route('users.addStats') }}" method="post">
                        @csrf
                        <input type="hidden" name="user_id" value="{{$user->id}}">
                        
                        <div class="table-responsive">
                            <table class="table table-hover text-center align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 30%;">Estadística</th>
                                        <th style="width: 30%;">Valor Actual</th>
                                        <th style="width: 40%;">Puntos a Asignar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $statIcons = [
                                            'Suerte' => '🎲',
                                            'Vitalidad' => '❤️',
                                            'Ingenio' => '💡',
                                            'Velocidad' => '⚡'
                                        ];
                                    @endphp
                                    @foreach ($user->userStats as $userStat)
                                        @php
                                            $statName = ucfirst($userStat->stat->name);
                                            $icon = $statIcons[$statName] ?? '📊';
                                        @endphp
                                        <tr>
                                            <td class="fw-bold fs-5">
                                                <span class="me-2">{{ $icon }}</span>
                                                {{ $statName }}
                                            </td>
                                            <td>
                                                <span class="badge bg-primary fs-6 px-3 py-2 stat-value" 
                                                      id="stat-value-{{ $userStat->stat->id }}" 
                                                      data-base-value="{{ $userStat->value }}">
                                                    {{ $userStat->value }}
                                                </span>
                                            </td>
                                            <td>
                                                <input type="number" 
                                                       name="stats[{{ $userStat->stat->id }}]" 
                                                       class="form-control form-control-lg text-center stat-input"
                                                       min="0" 
                                                       max="{{ $user->unasigned_points }}" 
                                                       value="0"
                                                       data-stat-id="{{ $userStat->stat->id }}"
                                                       style="max-width: 150px; margin: 0 auto;">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="d-flex gap-3 justify-content-center flex-wrap mt-4">
                            <button type="submit" class="btn btn-success btn-lg fw-bold px-5" id="assign-btn" disabled>
                                ✅ Asignar Puntos
                            </button>
                            
                            <x-action-button 
                                :href="route('users.show', $user->id)" 
                                text="🔙 Volver al Perfil" 
                                variant="outline-warning"
                                size="lg"
                            />
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (window.pages && window.pages.initStatsPointsControl) {
            window.pages.initStatsPointsControl({{ $user->unasigned_points }});
        }
    });
</script>
@endpush
@endsection

