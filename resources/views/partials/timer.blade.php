@if(isset($time_left) && $time_left > 0)
    <div id="floating-timer" class="fixed-timer">
        <span id="timer-label">⏳ Tiempo restante:</span> <span id="countdown"></span>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let timer = new easytimer.Timer();
            let timeLeft = {{ $time_left }}; // Segundos restantes

            // Iniciar el cronómetro
            timer.start({ countdown: true, startValues: { seconds: timeLeft } });

            // Actualizar la vista cada segundo
            timer.addEventListener('secondsUpdated', function () {
                document.getElementById('countdown').textContent = timer.getTimeValues().toString();
            });

            // Cuando el tiempo llegue a 0
            timer.addEventListener('targetAchieved', function () {
                document.getElementById('timer-label').textContent = ""; 
                document.getElementById('countdown').textContent = "✅ Acción completada";
                document.getElementById('floating-timer').classList.add('completed');
            });
        });
    </script>

    <style>
        .fixed-timer {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.9);
            padding: 10px 15px;
            border-radius: 10px;
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .completed {
            background: #28a745;
            color: white;
        }
    </style>
@endif
