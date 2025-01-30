@extends('layouts.full')

@section('title', 'Asignar Puntos')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card text-center shadow-lg border-0 rounded">
                <div class="card-body p-4">
                    
                    <h2 class="fw-bold mb-3">⭐ Asignar Puntos ⭐</h2>
                    
                    <p class="fs-5">Tienes <strong class="text-primary" id="remaining-points">{{ $user->unasigned_points }}</strong> puntos por asignar.</p>

                    <!-- Formulario -->
                    <form action="{{ route('users.addStats') }}" method="post">
                        @csrf
                        <input type="hidden" name="user_id" value="{{$user->_id}}">
                        <div class="table-responsive">
                            <table class="table table-striped text-center">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Estadística</th>
                                        <th>Valor Actual</th>
                                        <th>Puntos a Asignar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user->stats as $stat)
                                        <tr>
                                            <td class="fw-bold">{{ ucfirst($stat->stat->name) }}</td>
                                            <td class="stat-value" id="stat-value-{{ $stat->stat->id }}" data-base-value="{{ $stat->value }}">
                                                {{ $stat->value }}
                                            </td>
                                            <td>
                                                <input type="number" 
                                                       name="stats[{{ $stat->stat->id }}]" 
                                                       class="form-control text-center stat-input"
                                                       min="0" 
                                                       max="{{ $user->unasigned_points }}" 
                                                       value="0"
                                                       data-stat-id="{{ $stat->stat->id }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Botón de Asignar -->
                        <button type="submit" class="btn btn-success fw-bold mt-3" id="assign-btn" disabled>✅ Asignar Puntos</button>
                    </form>
                    
                    <!-- Botón de Volver -->
                    <div class="mt-3">
                        <a href="{{ route('users.show', $user->_id) }}" class="btn btn-warning fw-bold">🔙 Volver al Perfil</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Javascript para controlar los puntos -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let remainingPoints = {{ $user->unasigned_points }};
        const remainingPointsDisplay = document.getElementById("remaining-points");
        const assignBtn = document.getElementById("assign-btn");
        const inputs = document.querySelectorAll(".stat-input");

        function updateRemainingPoints() {
            let totalAssigned = 0;

            inputs.forEach(input => {
                totalAssigned += parseInt(input.value) || 0;
            });

            remainingPointsDisplay.textContent = remainingPoints - totalAssigned;

            // Bloquear valores que excedan los puntos disponibles
            inputs.forEach(input => {
                const maxAvailable = remainingPoints - totalAssigned + parseInt(input.value);
                input.max = maxAvailable >= 0 ? maxAvailable : 0;

                // Actualiza la vista con el nuevo valor de la estadística
                const statId = input.dataset.statId;
                const statValueElement = document.getElementById(`stat-value-${statId}`);
                const baseValue = parseInt(statValueElement.dataset.baseValue);
                statValueElement.textContent = baseValue + parseInt(input.value);
            });

            // Deshabilitar botón si no se han asignado todos los puntos
            assignBtn.disabled = totalAssigned !== remainingPoints;
        }

        inputs.forEach(input => {
            input.addEventListener("input", updateRemainingPoints);
        });
    });
</script>
@endsection

