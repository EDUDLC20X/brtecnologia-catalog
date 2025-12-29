<!-- Navigation Bar - B&R Tecnología - Diseño Profesional 2025 -->
@php
    $navbarLogo = \App\Models\SiteContent::get('global.navbar_logo');
    $navbarLogoUrl = content_image_url($navbarLogo, 'images/logo-br.png');
    $companyName = \App\Models\SiteContent::get('global.company_name', 'B&R Tecnología');
@endphp
<nav id="mainNavbar" class="navbar navbar-expand-lg sticky-top navbar-pro">
    <div class="container-xl">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <img src="{{ $navbarLogoUrl }}" alt="{{ $companyName }}" class="brand-logo"
                 onerror="this.onerror=null; this.src='{{ asset('images/logo-br.png') }}'">
        </a>

        <!-- Toggler Mobile -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <i class="bi bi-list fs-4"></i>
        </button>

        <!-- Menú colapsable -->
        <div class="collapse navbar-collapse" id="navbarMain">
            <!-- Navegación principal - CENTRADA -->
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                @if(auth()->check() && auth()->user()->isAdmin())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"><i class="bi bi-tools me-1"></i>Gestión</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('products.index') }}"><i class="bi bi-box-seam me-2"></i>Ver Productos</a></li>
                            <li><a class="dropdown-item" href="{{ route('products.create') }}"><i class="bi bi-plus-circle me-2"></i>Crear Producto</a></li>
                            <li><a class="dropdown-item" href="{{ route('products.export') }}"><i class="bi bi-file-earmark-arrow-down me-2"></i>Exportar Productos</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('admin.categories.index') }}"><i class="bi bi-tags me-2"></i>Gestionar Categorías</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('admin.content.index') }}"><i class="bi bi-pencil-square me-2"></i>Gestión de Contenido</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.content.section', 'global') }}"><i class="bi bi-image me-2"></i>Configurar Logos</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-envelope me-1"></i>Solicitudes
                            @php $pendingCount = \App\Models\ProductRequest::where('status', 'pending')->count(); @endphp
                            @if($pendingCount > 0)<span class="badge bg-danger ms-1">{{ $pendingCount }}</span>@endif
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('admin.requests.index') }}"><i class="bi bi-list-ul me-2"></i>Ver Todas</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.requests.index', ['status' => 'pending']) }}"><i class="bi bi-clock me-2 text-warning"></i>Pendientes</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.quotes.index') }}"><i class="bi bi-receipt me-2"></i>Cotizaciones</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('admin.stats.index') }}"><i class="bi bi-bar-chart me-2"></i>Estadísticas</a></li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('catalog.index') }}"><i class="bi bi-box-seam me-1"></i>Catálogo</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="{{ route('catalog.index', ['on_sale' => 1]) }}"><i class="bi bi-percent me-1"></i>Ofertas</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"><i class="bi bi-tags me-1"></i>Categorías</a>
                        <ul class="dropdown-menu">
                            @foreach(App\Models\Category::limit(8)->get() as $category)
                                <li><a class="dropdown-item" href="{{ route('catalog.index', ['categories' => [$category->id]]) }}">{{ $category->name }}</a></li>
                            @endforeach
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('catalog.index') }}">Ver todos</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('about') }}" style="white-space: nowrap;"><i class="bi bi-info-circle me-1"></i>Acerca&nbsp;de</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}"><i class="bi bi-envelope me-1"></i>Contacto</a></li>
                @endif
            </ul>

            <!-- Sección móvil: búsqueda y botones -->
            <div class="d-lg-none py-3">
                <form action="{{ route('catalog.index') }}" method="GET" class="mb-3">
                    <div class="input-group mobile-search">
                        <input type="search" name="search" class="form-control" placeholder="Buscar productos..." value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </form>
                @if(!auth()->check() || !auth()->user()->isAdmin())
                    <a href="{{ route('quote.index') }}" class="btn btn-outline-success w-100 mb-2">
                        <i class="bi bi-cart3 me-1"></i>Mi Cotización
                        <span id="quote-badge-mobile" class="badge bg-danger ms-1" style="display:none;">0</span>
                    </a>
                @endif
                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-primary w-100 mb-2"><i class="bi bi-box-arrow-in-right me-1"></i>Ingresar</a>
                    <a href="{{ route('register') }}" class="btn btn-primary w-100"><i class="bi bi-person-plus me-1"></i>Crear Cuenta</a>
                @endguest
                @auth
                    <!-- Menú de usuario móvil - Sin dropdown, opciones directas -->
                    <div class="mobile-user-menu">
                        <div class="mobile-user-header mb-3">
                            @if(auth()->user()->isAdmin())
                                <i class="bi bi-shield-check text-primary me-2"></i>
                            @else
                                <i class="bi bi-person-circle me-2"></i>
                            @endif
                            <span class="fw-bold">{{ auth()->user()->name }}</span>
                        </div>
                        
                        <div class="mobile-user-links">
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="mobile-user-link">
                                    <i class="bi bi-speedometer2"></i>Dashboard
                                </a>
                            @else
                                <a href="{{ route('client.dashboard') }}" class="mobile-user-link">
                                    <i class="bi bi-house"></i>Mi Panel
                                </a>
                                <a href="{{ route('client.favorites.index') }}" class="mobile-user-link">
                                    <i class="bi bi-heart"></i>Mis Favoritos
                                </a>
                                <a href="{{ route('client.requests.index') }}" class="mobile-user-link">
                                    <i class="bi bi-envelope"></i>Mis Solicitudes
                                </a>
                            @endif
                            <a href="{{ route('profile.edit') }}" class="mobile-user-link">
                                <i class="bi bi-gear"></i>Configuración
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline w-100">
                                @csrf
                                <button type="submit" class="mobile-user-link text-danger w-100 text-start border-0 bg-transparent">
                                    <i class="bi bi-box-arrow-right"></i>Cerrar sesión
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
        </div>

        <!-- Acciones Desktop -->
        <div class="d-none d-lg-flex align-items-center gap-2">
            <form action="{{ route('catalog.index') }}" method="GET">
                <div class="search-box-pro">
                    <i class="bi bi-search search-icon"></i>
                    <input type="search" name="search" placeholder="Buscar productos..." value="{{ request('search') }}">
                </div>
            </form>
            @if(!auth()->check() || !auth()->user()->isAdmin())
                <a href="{{ route('quote.index') }}" class="btn btn-cart-pro position-relative" title="Mi Cotización">
                    <i class="bi bi-cart3"></i>
                    <span id="quote-badge" class="quote-badge-pro" style="display:none;">0</span>
                </a>
            @endif
            @guest
                <a href="{{ route('login') }}" class="btn btn-login-pro"><i class="bi bi-person me-1"></i>Ingresar</a>
                <a href="{{ route('register') }}" class="btn btn-register-pro"><i class="bi bi-person-plus me-1"></i>Crear Cuenta</a>
            @endguest
            @auth
                <div class="dropdown">
                    <a class="btn btn-user-pro dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <div class="user-avatar">
                            @if(auth()->user()->isAdmin())<i class="bi bi-shield-check"></i>@else<i class="bi bi-person"></i>@endif
                        </div>
                        <span class="user-name">{{ Str::limit(auth()->user()->name, 12) }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-pro">
                        @if(auth()->user()->isAdmin())
                            <li class="dropdown-header-pro">
                                <i class="bi bi-shield-check text-primary me-2"></i>Administrador
                            </li>
                            <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                        @else
                            <li class="dropdown-header-pro">
                                <i class="bi bi-person-check text-success me-2"></i>Mi Cuenta
                            </li>
                            <li><a class="dropdown-item" href="{{ route('client.dashboard') }}"><i class="bi bi-house me-2"></i>Mi Panel</a></li>
                            <li><a class="dropdown-item" href="{{ route('client.favorites.index') }}"><i class="bi bi-heart me-2 text-danger"></i>Mis Favoritos</a></li>
                            <li><a class="dropdown-item" href="{{ route('client.requests.index') }}"><i class="bi bi-envelope me-2"></i>Mis Solicitudes</a></li>
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-gear me-2"></i>Configuración</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión</button></form></li>
                    </ul>
                </div>
            @endauth
        </div>
    </div>
</nav>

<style>
/* ============================================
   NAVBAR PROFESIONAL 2025 - B&R Tecnología
   ============================================ */

.navbar-pro {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);
    padding: 0.5rem 0;
    transition: all 0.3s ease;
    border-bottom: 1px solid rgba(255,255,255,0.08);
    backdrop-filter: blur(10px);
}

.navbar-pro.scrolled {
    background: rgba(15, 23, 42, 0.98);
    box-shadow: 0 4px 30px rgba(0,0,0,0.2);
}

/* Logo */
.navbar-pro .brand-logo {
    height: 70px;
    width: auto;
    max-width: 260px;
    object-fit: contain;
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
    transition: transform 0.3s ease;
}

.navbar-pro .brand-logo:hover {
    transform: scale(1.03);
}

/* Enlaces de Navegación */
.navbar-pro .nav-link {
    color: rgba(255,255,255,0.85) !important;
    font-weight: 500;
    font-size: 0.9rem;
    padding: 0.6rem 1rem;
    border-radius: 10px;
    transition: all 0.25s ease;
    display: flex;
    align-items: center;
    gap: 0.35rem;
}

.navbar-pro .nav-link:hover,
.navbar-pro .nav-link:focus {
    color: #ffffff !important;
    background: rgba(255,255,255,0.1);
}

.navbar-pro .nav-link.text-danger {
    color: #f87171 !important;
    background: rgba(248, 113, 113, 0.1);
}

.navbar-pro .nav-link.text-danger:hover {
    background: rgba(248, 113, 113, 0.2);
}

/* Dropdowns Mejorados */
.navbar-pro .dropdown-menu {
    background: #ffffff;
    border: none;
    border-radius: 16px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.15), 0 0 0 1px rgba(0,0,0,0.05);
    padding: 0.75rem;
    margin-top: 0.75rem;
    min-width: 240px;
    animation: dropdownFadeIn 0.2s ease;
}

@keyframes dropdownFadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.navbar-pro .dropdown-item {
    padding: 0.75rem 1rem;
    border-radius: 10px;
    font-size: 0.9rem;
    color: #334155;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
}

.navbar-pro .dropdown-item:hover {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(14, 165, 233, 0.1));
    color: #3b82f6;
    transform: translateX(4px);
}

.navbar-pro .dropdown-item i {
    width: 24px;
    text-align: center;
    font-size: 1rem;
}

.navbar-pro .dropdown-divider {
    margin: 0.5rem 0.75rem;
    border-color: rgba(0,0,0,0.06);
}

.dropdown-header-pro {
    padding: 0.5rem 1rem;
    font-size: 0.75rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.dropdown-pro {
    border-radius: 16px !important;
}

/* Buscador Desktop Mejorado */
.search-box-pro {
    position: relative;
    width: 220px;
}

.search-box-pro .search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: rgba(255,255,255,0.5);
    font-size: 0.9rem;
    pointer-events: none;
    transition: color 0.2s;
}

.search-box-pro input {
    width: 100%;
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 50px;
    color: #ffffff;
    font-size: 0.875rem;
    padding: 0.6rem 1rem 0.6rem 2.5rem;
    transition: all 0.3s ease;
}

.search-box-pro input::placeholder {
    color: rgba(255,255,255,0.5);
}

.search-box-pro input:focus {
    outline: none;
    background: rgba(255,255,255,0.15);
    border-color: rgba(255,255,255,0.3);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
}

.search-box-pro:focus-within .search-icon {
    color: rgba(255,255,255,0.8);
}

/* Botón de Carrito */
.btn-cart-pro {
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(16, 185, 129, 0.15);
    border: 1px solid rgba(16, 185, 129, 0.3);
    border-radius: 12px;
    color: #34d399;
    font-size: 1.25rem;
    transition: all 0.3s ease;
    position: relative;
}

.btn-cart-pro:hover {
    background: #10b981;
    border-color: #10b981;
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
}

.quote-badge-pro {
    position: absolute;
    top: -6px;
    right: -6px;
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    font-size: 0.65rem;
    font-weight: 700;
    padding: 0.2em 0.5em;
    border-radius: 50px;
    min-width: 18px;
    text-align: center;
    animation: pulse 2s infinite;
}

/* Botones de Login/Register */
.btn-login-pro {
    background: transparent;
    border: 1px solid rgba(255,255,255,0.25);
    color: rgba(255,255,255,0.9);
    padding: 0.55rem 1.25rem;
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-login-pro:hover {
    background: rgba(255,255,255,0.1);
    border-color: rgba(255,255,255,0.4);
    color: #ffffff;
}

.btn-register-pro {
    background: linear-gradient(135deg, #3b82f6, #0ea5e9);
    border: none;
    color: #ffffff;
    padding: 0.55rem 1.25rem;
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
}

.btn-register-pro:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
    color: #ffffff;
}

/* Botón de Usuario */
.btn-user-pro {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.15);
    color: rgba(255,255,255,0.9);
    padding: 0.4rem 1rem 0.4rem 0.5rem;
    border-radius: 50px;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.btn-user-pro:hover {
    background: rgba(255,255,255,0.15);
    border-color: rgba(255,255,255,0.25);
    color: #ffffff;
}

.btn-user-pro .user-avatar {
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, #3b82f6, #0ea5e9);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    color: white;
}

.btn-user-pro .user-name {
    font-weight: 500;
}

/* Toggler Móvil */
.navbar-pro .navbar-toggler {
    color: rgba(255,255,255,0.9);
    padding: 0.5rem;
    border-radius: 10px;
    transition: all 0.2s;
}

.navbar-pro .navbar-toggler:hover {
    background: rgba(255,255,255,0.1);
}

.navbar-pro .navbar-toggler:focus {
    box-shadow: none;
}

/* Menú Móvil */
@media (max-width: 991.98px) {
    #navbarMain {
        background: linear-gradient(180deg, rgba(15, 23, 42, 0.98), rgba(30, 58, 95, 0.98));
        margin-top: 1rem;
        padding: 1.5rem;
        border-radius: 20px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.4);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.1);
        position: relative;
        z-index: 1050;
    }
    
    #navbarMain .dropdown-menu {
        position: relative !important;
        transform: none !important;
        width: 100%;
        background: rgba(0,0,0,0.3) !important;
        z-index: 1051;
    }
    
    .navbar-pro .navbar-nav {
        margin-bottom: 1.5rem;
    }
    
    .navbar-pro .nav-link {
        padding: 1rem 1.25rem !important;
        font-size: 1rem !important;
        margin: 0.2rem 0;
        border-radius: 12px;
    }
    
    .navbar-pro .dropdown-menu {
        background: rgba(0,0,0,0.3);
        box-shadow: none;
        border: 1px solid rgba(255,255,255,0.1);
        margin: 0.5rem 0 1rem 0;
    }
    
    .navbar-pro .dropdown-item {
        color: rgba(255,255,255,0.85);
        padding: 0.85rem 1.25rem;
    }
    
    .navbar-pro .dropdown-item:hover {
        background: rgba(255,255,255,0.1);
        color: #ffffff;
        transform: none;
    }
    
    .navbar-pro .dropdown-divider {
        border-color: rgba(255,255,255,0.1);
    }
    
    .dropdown-header-pro {
        color: rgba(255,255,255,0.6);
    }
    
    .mobile-search-pro {
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 14px;
        overflow: hidden;
    }
    
    .mobile-search-pro input {
        background: transparent !important;
        border: none !important;
        color: #ffffff !important;
        padding: 0.85rem 1rem;
    }
    
    .mobile-search-pro input::placeholder {
        color: rgba(255,255,255,0.5) !important;
    }
    
    .mobile-search-pro .btn {
        background: linear-gradient(135deg, #3b82f6, #0ea5e9);
        border: none;
        color: white;
        border-radius: 0;
    }
    
    /* Menú de usuario móvil */
    .mobile-user-menu {
        background: rgba(255,255,255,0.05);
        border-radius: 16px;
        padding: 1rem;
        border: 1px solid rgba(255,255,255,0.1);
    }
    
    .mobile-user-header {
        display: flex;
        align-items: center;
        color: #ffffff;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        font-size: 1.1rem;
    }
    
    .mobile-user-links {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        margin-top: 0.75rem;
    }
    
    .mobile-user-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.85rem 1rem;
        color: rgba(255,255,255,0.85);
        text-decoration: none;
        border-radius: 10px;
        transition: all 0.2s;
        font-size: 0.95rem;
    }
    
    .mobile-user-link:hover {
        background: rgba(255,255,255,0.1);
        color: #ffffff;
    }
    
    .mobile-user-link i {
        font-size: 1.1rem;
        width: 24px;
        text-align: center;
    }
}

@media (min-width: 992px) {
    .navbar-pro .brand-logo {
        height: 75px;
        max-width: 280px;
    }
}

/* Animación de pulse para badge */
@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}
</style>

<script>
(function(){
    var nav = document.getElementById('mainNavbar');
    function onScroll(){ nav.classList.toggle('scrolled', window.scrollY > 10); }
    document.addEventListener('DOMContentLoaded', onScroll);
    window.addEventListener('scroll', onScroll);
    
    function updateQuoteBadge() {
        var badge = document.getElementById('quote-badge');
        var badgeMobile = document.getElementById('quote-badge-mobile');
        fetch('{{ route("quote.count") }}', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                var count = data.count || 0;
                [badge, badgeMobile].forEach(function(b) {
                    if (b) { b.textContent = count; b.style.display = count > 0 ? 'inline' : 'none'; }
                });
            }).catch(function() {});
    }
    document.addEventListener('DOMContentLoaded', updateQuoteBadge);
    window.updateQuoteBadge = updateQuoteBadge;
})();
</script>