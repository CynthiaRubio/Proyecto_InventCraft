<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="/zones">Mapa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/zones">Inventario</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/buildings">Edificios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('users.ranking') }}">Ranking</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/materialTypes">Tipos de Materiales</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/inventionTypes">Tipos de Inventos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/materials">Materiales</a>
                </li>
            </ul>

            <ul class="navbar-nav">
                @auth
                    <!-- Si el usuario está autenticado -->
                
                    <li class="nav-item">
                    <a href="{{ route('logout') }}" class="btn btn-primary">Cierra sesión</a>
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