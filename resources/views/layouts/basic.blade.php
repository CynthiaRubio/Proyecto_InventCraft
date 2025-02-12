<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;1,400&display=swap" rel="stylesheet">
    <!--Crono-->
    <script src="https://cdn.jsdelivr.net/npm/easytimer.js@4.4.0/dist/easytimer.min.js"></script>
    <title>@yield('title', 'Juego individual')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @include('partials.header')

    @yield('nav')

    @yield('timer')

    @if(session('error'))
    <div class="container mt-4">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h4 class="alert-heading">¡Error!</h4>
            <p>{{ session('error') }}</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif

    @if ($errors->any())
    <div class="container mt-4">
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading">¡Error!</h4>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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
    <div class="alert alert-success">
        <ul>
            @foreach (session('data') as $resource)
                @foreach ($resource as $name => $quantity)
                    <li>Has recolectado {{$name}}: {{ $quantity }}</li>
                @endforeach
            @endforeach
        </ul>
    </div>
    @endif


    <div class="container mt-5">
        @yield('content')
    </div>

    @include('partials.footer')

</body>
</html>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
