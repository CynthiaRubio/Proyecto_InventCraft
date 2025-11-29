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
    @stack('styles')
</head>
<body>
    <div class="main-content-wrapper pb-5">
    @unless(Request::routeIs('home') || Request::routeIs('login') || Request::routeIs('register'))
        @include('partials.header-nav')
    @endunless

    @yield('nav')

    @if(isset($time_left) && $time_left > 0)
        <x-floating-timer :timeLeft="$time_left" />
    @endif

    <x-validation-errors />
    <x-flash-messages />

    @yield('content')
    </div>

    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
