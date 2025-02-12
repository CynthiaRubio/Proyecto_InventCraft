<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
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
                    <a class="nav-link {{ Request::routeIs('inventories.index') ? 'active' : '' }}" href="{{ route('inventories.index') }}">Inventario</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.show' , auth()->id()) }}">Mi perfil</a>
                </li>
            </ul>

            <ul class="navbar-nav">
                @auth
                    <!-- Si el usuario está autenticado -->
                
                    <li class="nav-item">
                    <a href="{{ route('logout') }}" class="btn btn-primary">Cerrar sesión</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                @endauth

                @guest
                    <!-- Si el usuario no está autenticado -->
                    <li class="nav-item">
                        <a class="nav-link btn btn-success text-white" href="{{ route('login') }}">Iniciar sesión</a>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>