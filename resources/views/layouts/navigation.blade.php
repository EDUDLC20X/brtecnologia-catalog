
<!-- Navigation Bar - B&R Tecnología (Modernized) -->
@php
    // Cargar logo de navegación desde el CMS
    $navbarLogo = \App\Models\SiteContent::get('global.navbar_logo');
    $navbarLogoUrl = null;
    
    if ($navbarLogo) {
        if (\Illuminate\Support\Str::startsWith($navbarLogo, 'content/')) {
            $navbarLogoUrl = asset('storage/' . $navbarLogo);
        } else {
            $navbarLogoUrl = asset($navbarLogo);
        }
    } else {
        $navbarLogoUrl = asset('images/logo-br.png');
    }
    
    $companyName = \App\Models\SiteContent::get('global.company_name', 'B&R Tecnología');
@endphp
<nav id="mainNavbar" class="navbar navbar-expand-lg navbar-light bg-white sticky-top" style="height: 64px; min-height: 64px; max-height: 64px;">
    <div class="container-xl">
        <!-- Brand: Logo Image - CMS Managed -->
        <a class="navbar-brand d-flex align-items-center pe-3" href="{{ route('home') }}" style="height: 48px; overflow: hidden;">
            <img src="{{ $navbarLogoUrl }}" 
                 alt="{{ $companyName }}" 
                 class="navbar-logo"
                 style="max-height: 150px; max-width: 150px; width: auto; height: auto; object-fit: contain;"
                 onerror="this.onerror=null; this.src='{{ asset('images/logo-br.png') }}'">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navCollapse" aria-controls="navCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navCollapse">
            <!-- Primary navigation: show client links to guests and non-admins; show admin links to admins -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-lg-center">
                @if(auth()->check() && auth()->user()->isAdmin())
                    <li class="nav-item mx-lg-1">
                        <a class="nav-link d-flex align-items-center" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a>
                    </li>
                    <li class="nav-item dropdown mx-lg-1">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="adminManage" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-tools me-1"></i>Gestión</a>
                        <ul class="dropdown-menu" aria-labelledby="adminManage">
                            <li><a class="dropdown-item" href="{{ route('products.index') }}"><i class="bi bi-box-seam me-2"></i>Ver Productos</a></li>
                            <li><a class="dropdown-item" href="{{ route('products.create') }}"><i class="bi bi-plus-circle me-2"></i>Crear Producto</a></li>
                            <li><a class="dropdown-item" href="{{ route('products.export') }}"><i class="bi bi-file-earmark-arrow-down me-2"></i>Exportar Productos</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('admin.content.index') }}"><i class="bi bi-pencil-square me-2"></i>Gestión de Contenido</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.content.section', 'global') }}"><i class="bi bi-image me-2"></i>Configurar Logos</a></li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item mx-lg-1">
                        <a class="nav-link d-flex align-items-center" href="{{ route('catalog.index') }}"><i class="bi bi-box-seam me-1" style="font-size:1rem;"></i>Catálogo</a>
                    </li>
                    <li class="nav-item mx-lg-1">
                        <a class="nav-link d-flex align-items-center text-danger" href="{{ route('catalog.index', ['on_sale' => 1]) }}"><i class="bi bi-percent me-1" style="font-size:0.98rem;"></i>Ofertas</a>
                    </li>
                    <li class="nav-item dropdown mx-lg-1">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="categoriesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-tags me-1" style="font-size:0.98rem;"></i>Categorías</a>
                        <ul class="dropdown-menu" aria-labelledby="categoriesDropdown">
                            @foreach(App\Models\Category::limit(8)->get() as $category)
                                <li><a class="dropdown-item" href="{{ route('catalog.index', ['categories' => [$category->id]]) }}">{{ $category->name }}</a></li>
                            @endforeach
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('catalog.index') }}">Ver todos</a></li>
                        </ul>
                    </li>
                    <li class="nav-item mx-lg-1">
                        <a class="nav-link d-flex align-items-center" href="{{ route('about') }}"><i class="bi bi-info-circle me-1" style="font-size:0.98rem;"></i>Acerca de</a>
                    </li>
                    <li class="nav-item mx-lg-1">
                        <a class="nav-link d-flex align-items-center" href="{{ route('contact') }}"><i class="bi bi-envelope me-1" style="font-size:0.98rem;"></i>Contacto</a>
                    </li>
                @endif
            </ul>

            <!-- Actions: search, auth -->
            <div class="d-flex align-items-center gap-2">
                <form class="d-none d-md-flex" action="{{ route('catalog.index') }}" method="GET" role="search">
                    <div class="input-group input-group-sm" style="width:320px;">
                        <input type="search" name="search" class="form-control" placeholder="Buscar productos..." value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit" aria-label="Buscar"><i class="bi bi-search"></i></button>
                    </div>
                </form>

                {{-- El botón de crear cuenta se eliminó - Solo acceso administrativo --}}

                @auth
                    <div class="dropdown">
                        <a class="btn btn-outline-secondary btn-navbar dropdown-toggle" href="#" role="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Mi perfil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">@csrf
                                    <button type="submit" class="dropdown-item">Cerrar sesión</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <style>
        /* Subtle shadow when page is scrolled */
        #mainNavbar.scrolled { box-shadow: 0 6px 20px rgba(13,38,76,0.08); }
        .navbar-brand small { opacity: 0.9; }
        .nav-link { color: #0f1724; }
        .nav-link:hover, .nav-link:focus { color: #0d6efd; }
        .btn-primary { background-color: #0d6efd; border-color: #0d6efd; }
    </style>

    <script>
        (function(){
            var nav = document.getElementById('mainNavbar');
            function onScroll(){
                if(window.scrollY > 10){ nav.classList.add('scrolled'); }
                else { nav.classList.remove('scrolled'); }
            }
            document.addEventListener('DOMContentLoaded', onScroll);
            window.addEventListener('scroll', onScroll);
        })();
    </script>
</nav>