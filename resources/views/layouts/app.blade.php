<!-- Esta es la plantilla de las vistas hijas -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Juego individual')</title> <!-- En esta plantilla las hijas podrán insertar contenido -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @include('partials.header') <!-- Con esto incluimos el header de la carpeta partials -->

    @yield(section('nav')) <!-- Si desde las hijas llaman a esta sección, la recuperará de partials.nav -->
    
    <div class="container mt-5">
        @yield('content') <!-- En esta plantilla las hijas podrán insertar contenido -->
    </div>
    
    @include('partials.footer') <!-- Con esto incluimos el footer de la carpeta partials -->
</body>
</html>
