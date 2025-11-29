<div class="accordion-item border-light">
    <h2 class="accordion-header">
        <button class="accordion-button collapsed bg-light text-dark fw-bold" 
                type="button" 
                data-bs-toggle="collapse" 
                data-bs-target="{{ $targetClass() }}">
            <strong>{{ $title }}</strong>
            @if($count !== null)
                <span class="ms-2">({{ $count }})</span>
            @endif
        </button>
    </h2>
    <div class="accordion-collapse collapse {{ $collapseId() }}">
        <div class="accordion-body">
            {{ $slot }}
        </div>
    </div>
</div>

