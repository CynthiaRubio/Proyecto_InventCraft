<div class="card shadow-sm mb-4">
    <div class="card-body">
        @if($title)
            <h5 class="card-title mb-3">{{ $title }}</h5>
        @endif
        <dl class="row">
            @foreach($items as $label => $value)
                <dt class="col-sm-4">{{ $label }}:</dt>
                <dd class="col-sm-8">{{ $value }}</dd>
            @endforeach
            {{ $slot }}
        </dl>
    </div>
</div>

