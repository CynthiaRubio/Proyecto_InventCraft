@if(session('error'))
<div class="container mt-4">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <h4 class="alert-heading">¡Error!</h4>
        <p>{{ session('error') }}</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
@endif

@if(session('success'))
<div class="container mt-4">
    <div class="alert alert-success" role="alert">
        <h4 class="alert-heading">¡Felicidades!</h4>
        <p>{{ session('success') }}</p>
    </div>
</div>
@endif

@if(session('data'))
<div class="container mt-4">
    <div class="alert alert-success" role="alert">
        @if(session('data')['message'] ?? null)
            <h4 class="alert-heading">{{ session('data')['message'] }}</h4>
        @endif
        @if(isset(session('data')['items']) && is_array(session('data')['items']))
            <ul class="mb-0">
                @foreach (session('data')['items'] as $resource)
                    @if(is_array($resource))
                        @foreach ($resource as $name => $quantity)
                            <li>{{ $name }}: {{ $quantity }}</li>
                        @endforeach
                    @else
                        <li>{{ $resource }}</li>
                    @endif
                @endforeach
            </ul>
        @else
            <ul class="mb-0">
                @foreach (session('data') as $resource)
                    @if(is_array($resource) && isset($resource['name']) && isset($resource['type']))
                        <li>{{ $resource['name'] }} ({{ $resource['type'] }}): {{ $resource['quantity'] }}</li>
                    @elseif(is_array($resource))
                        @foreach ($resource as $name => $quantity)
                            <li>Has recolectado {{ $name }}: {{ $quantity }}</li>
                        @endforeach
                    @else
                        <li>Has recolectado {{ $resource }}</li>
                    @endif
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endif

