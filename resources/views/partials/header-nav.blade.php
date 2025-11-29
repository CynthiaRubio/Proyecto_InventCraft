<header class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container-fluid">
        <!-- Logo -->
        <a href="{{ route('users.show') }}" class="navbar-brand d-flex align-items-center me-4">
            <img src="{{ asset('images/home/logo_min.png') }}" alt="Logo de InventCraft" class="img-fluid" style="max-height: 60px;">
        </a>

        <!-- Botón toggle para móviles -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navegación -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('zones.*') ? 'active' : '' }}" href="{{ route('zones.index') }}">Mapa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('events.*') ? 'active' : '' }}" href="{{ route('events.index') }}">Eventos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('buildings.*') ? 'active' : '' }}" href="{{ route('buildings.index') }}">Edificios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('inventionTypes.*') ? 'active' : '' }}" href="{{ route('inventionTypes.index') }}">Tipos de Inventos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('materialTypes.*') ? 'active' : '' }}" href="{{ route('materialTypes.index') }}">Tipos de Materiales</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('users.ranking') ? 'active' : '' }}" href="{{ route('users.ranking') }}">Ranking</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('users.show') ? 'active' : '' }}" href="{{ route('users.show') }}">Mi perfil</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-3">
                <li class="nav-item">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm">Cerrar sesión</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>

