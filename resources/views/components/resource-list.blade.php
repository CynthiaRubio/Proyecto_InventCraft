@props([
    'items' => [],
    'emptyMessage' => 'No hay elementos disponibles.',
    'showButton' => false,
    'buttonText' => 'Ver',
    'buttonRoute' => null,
    'buttonParam' => null,
    'itemNameAttribute' => 'name',
])

@if($items->isEmpty())
    <p class="text-center text-danger">{{ $emptyMessage }}</p>
@else
    <ul class="list-group">
        @foreach($items as $item)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span class="fw-bold text-dark">
                    @if(isset($item->{$itemNameAttribute}))
                        {{ $item->{$itemNameAttribute} }}
                    @else
                        {{ $slot }}
                    @endif
                </span>
                @if($showButton && $buttonRoute)
                    <a href="{{ route($buttonRoute, $buttonParam ?? $item->id ?? $item) }}" 
                       class="btn btn-warning btn-sm">
                        {{ $buttonText }}
                    </a>
                @endif
            </li>
        @endforeach
    </ul>
@endif

