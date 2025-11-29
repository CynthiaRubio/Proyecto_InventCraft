// ============================================
// Timer Flotante
// ============================================
export function initFloatingTimer(timeLeft) {
    if (typeof easytimer === 'undefined') {
        console.error('EasyTimer no está disponible');
        return;
    }

    const timer = new easytimer.Timer();
    const timerLabel = document.getElementById('timer-label');
    const countdown = document.getElementById('countdown');
    const floatingTimer = document.getElementById('floating-timer');

    if (!timerLabel || !countdown || !floatingTimer) {
        return;
    }

    // Iniciar el cronómetro
    timer.start({ countdown: true, startValues: { seconds: timeLeft } });

    // Actualizar la vista cada segundo
    timer.addEventListener('secondsUpdated', function () {
        countdown.textContent = timer.getTimeValues().toString();
    });

    // Cuando el tiempo llegue a 0
    timer.addEventListener('targetAchieved', function () {
        timerLabel.textContent = "";
        countdown.textContent = "✅ Acción completada";
        floatingTimer.classList.add('completed');
    });
}

// ============================================
// Control de Puntos de Estadísticas
// ============================================
export function initStatsPointsControl(remainingPoints) {
    const remainingPointsDisplay = document.getElementById("remaining-points");
    const assignBtn = document.getElementById("assign-btn");
    const inputs = document.querySelectorAll(".stat-input");

    if (!remainingPointsDisplay || !assignBtn || inputs.length === 0) {
        return;
    }

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
            if (statValueElement) {
                const baseValue = parseInt(statValueElement.dataset.baseValue);
                statValueElement.textContent = baseValue + parseInt(input.value);
            }
        });

        // Deshabilitar botón si no se han asignado todos los puntos
        assignBtn.disabled = totalAssigned !== remainingPoints;
    }

    inputs.forEach(input => {
        input.addEventListener("input", updateRemainingPoints);
    });
}

