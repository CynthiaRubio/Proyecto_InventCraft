@props([
    'imagePath' => '',
    'alt' => '',
    'title' => '',
    'description' => null,
    'imageSize' => 'img-fluid',
    'showShadow' => true,
])

<div class="card shadow-sm mb-4">
    @if($imagePath)
        <div class="text-center mb-3">
            <img src="{{ $imagePath }}" 
                 alt="{{ $alt }}" 
                 class="{{ $imageSize }} rounded {{ $showShadow ? 'shadow-lg' : '' }}">
        </div>
    @endif
    @if($title || $description || $slot->isNotEmpty())
        <div class="card-body">
            @if($title)
                <h5 class="card-title">{{ $title }}</h5>
            @endif
            @if($description)
                <p class="card-text">{{ $description }}</p>
            @endif
            {{ $slot }}
        </div>
    @endif
</div>

