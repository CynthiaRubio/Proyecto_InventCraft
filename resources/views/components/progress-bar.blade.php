<div class="mb-3">
    @if($label)
        <h6 class="text-light mb-2">{{ $label }}</h6>
    @endif
    <div class="progress" style="height: {{ $height }};">
        <div class="progress-bar {{ $progressBarClasses() }}" 
             role="progressbar" 
             style="width: {{ $percentage() }}%;" 
             aria-valuenow="{{ $value }}" 
             aria-valuemin="0" 
             aria-valuemax="{{ $max }}">
            {{ $displayValue() }}
        </div>
    </div>
</div>

