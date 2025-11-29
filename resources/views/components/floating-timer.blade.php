@if($timeLeft > 0)
<div id="floating-timer" class="fixed-timer">
    <span id="timer-label">⏳ Tiempo restante:</span> <span id="countdown"></span>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Esperar a que EasyTimer esté disponible
        if (typeof easytimer !== 'undefined') {
            let timer = new easytimer.Timer();
            let timeLeft = {{ $timeLeft }};
            const timerLabel = document.getElementById('timer-label');
            const countdown = document.getElementById('countdown');
            const floatingTimer = document.getElementById('floating-timer');

            if (timerLabel && countdown && floatingTimer) {
                timer.start({ countdown: true, startValues: { seconds: timeLeft } });

                timer.addEventListener('secondsUpdated', function () {
                    countdown.textContent = timer.getTimeValues().toString();
                });

                timer.addEventListener('targetAchieved', function () {
                    timerLabel.textContent = "";
                    countdown.textContent = "✅ Acción completada";
                    floatingTimer.classList.add('completed');
                });
            }
        }
    });
</script>
@endpush
@endif

