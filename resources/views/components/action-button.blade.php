<a href="{{ $href }}" class="{{ $buttonClasses() }}">
    @if($icon)
        <span class="me-2">{{ $icon }}</span>
    @endif
    {{ $text }}
</a>

