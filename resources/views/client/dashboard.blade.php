@extends('layouts.app')

@section('title', 'Mi Cuenta')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="client-page-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h1 style="color:#fef2f2;"><i class="bi bi-person-circle me-2"  style="color:#fef2f2;"></i>¡Hola, {{ $user->name }}!</h1>
                <p>Bienvenido a tu panel de cliente</p>
            </div>
            <div class="quick-actions">
                <a href="{{ route('catalog.index') }}" class="quick-action-btn">
                    <i class="bi bi-shop"></i> Ver Catálogo
                </a>
                <a href="{{ route('profile.edit') }}" class="quick-action-btn">
                    <i class="bi bi-gear"></i> Mi Perfil
                </a>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="stats-grid">
        <a href="{{ route('client.favorites.index') }}" class="stat-card text-decoration-none">
            <div class="stat-icon favorites">
                <i class="bi bi-heart-fill"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $favoritesCount }}</h3>
                <p>Favoritos guardados</p>
            </div>
        </a>
        <a href="{{ route('client.history.index') }}" class="stat-card text-decoration-none">
            <div class="stat-icon history">
                <i class="bi bi-clock-history"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $recentViews->count() }}</h3>
                <p>Vistos recientemente</p>
            </div>
        </a>
        <a href="{{ route('client.requests.index') }}" class="stat-card text-decoration-none">
            <div class="stat-icon requests">
                <i class="bi bi-envelope-check"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $requestsCount }}</h3>
                <p>Solicitudes totales</p>
            </div>
        </a>
        <a href="{{ route('client.requests.index') }}?status=pending" class="stat-card text-decoration-none">
            <div class="stat-icon pending">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $pendingRequestsCount }}</h3>
                <p>Solicitudes pendientes</p>
            </div>
        </a>
    </div>

    <div class="row">
        <!-- Favoritos Recientes -->
        <div class="col-lg-6 mb-4">
            <div class="section-card">
                <div class="section-header">
                    <h2><i class="bi bi-heart text-danger"></i> Mis Favoritos</h2>
                    @if($recentFavorites->count() > 0)
                        <a href="{{ route('client.favorites.index') }}">Ver todos <i class="bi bi-arrow-right"></i></a>
                    @endif
                </div>
                <div class="section-body">
                    @if($recentFavorites->count() > 0)
                        <div class="products-mini-grid">
                            @foreach($recentFavorites as $favorite)
                                @if($favorite->product)
                                    <a href="{{ route('catalog.show', $favorite->product) }}" class="product-mini-card">
                                        @if($favorite->product->mainImage)
                                            <img src="{{ image_url($favorite->product->mainImage->path) }}" alt="{{ $favorite->product->name }}" onerror="this.parentElement.querySelector('.no-image').style.display='flex';this.style.display='none';">
                                            <div class="no-image" style="display:none;"><i class="bi bi-image"></i></div>
                                        @else
                                            <div class="no-image"><i class="bi bi-image"></i></div>
                                        @endif
                                        <h4>{{ Str::limit($favorite->product->name, 35) }}</h4>
                                        <div class="price">${{ number_format($favorite->product->price_base, 2) }}</div>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="bi bi-heart"></i>
                            <p>No tienes productos favoritos aún.<br><a href="{{ route('catalog.index') }}">Explorar catálogo</a></p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Historial Reciente -->
        <div class="col-lg-6 mb-4">
            <div class="section-card">
                <div class="section-header">
                    <h2><i class="bi bi-clock-history text-primary"></i> Visto Recientemente</h2>
                    @if($recentViews->count() > 0)
                        <a href="{{ route('client.history.index') }}">Ver todo <i class="bi bi-arrow-right"></i></a>
                    @endif
                </div>
                <div class="section-body">
                    @if($recentViews->count() > 0)
                        <div class="products-mini-grid">
                            @foreach($recentViews as $view)
                                @if($view->product)
                                    <a href="{{ route('catalog.show', $view->product) }}" class="product-mini-card">
                                        @if($view->product->mainImage)
                                            <img src="{{ image_url($view->product->mainImage->path) }}" alt="{{ $view->product->name }}" onerror="this.parentElement.querySelector('.no-image').style.display='flex';this.style.display='none';">
                                            <div class="no-image" style="display:none;"><i class="bi bi-image"></i></div>
                                        @else
                                            <div class="no-image"><i class="bi bi-image"></i></div>
                                        @endif
                                        <h4>{{ Str::limit($view->product->name, 35) }}</h4>
                                        <div class="price">${{ number_format($view->product->price_base, 2) }}</div>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="bi bi-clock-history"></i>
                            <p>No has visto productos aún.<br><a href="{{ route('catalog.index') }}">Explorar catálogo</a></p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recomendaciones -->
        @if($recommendations->count() > 0)
        <div class="col-lg-6 mb-4">
            <div class="section-card">
                <div class="section-header">
                    <h2><i class="bi bi-stars text-warning"></i> Recomendados para ti</h2>
                    <a href="{{ route('catalog.index') }}">Ver más <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="section-body">
                    <div class="products-mini-grid">
                        @foreach($recommendations as $product)
                            <a href="{{ route('catalog.show', $product) }}" class="product-mini-card">
                                @if($product->mainImage)
                                    <img src="{{ image_url($product->mainImage->path) }}" alt="{{ $product->name }}" onerror="this.parentElement.querySelector('.no-image').style.display='flex';this.style.display='none';">
                                    <div class="no-image" style="display:none;"><i class="bi bi-image"></i></div>
                                @else
                                    <div class="no-image"><i class="bi bi-image"></i></div>
                                @endif
                                <h4>{{ Str::limit($product->name, 35) }}</h4>
                                <div class="price">${{ number_format($product->price_base, 2) }}</div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Solicitudes Recientes -->
        <div class="col-lg-{{ $recommendations->count() > 0 ? '6' : '12' }} mb-4">
            <div class="section-card">
                <div class="section-header">
                    <h2 ><i class="bi bi-envelope text-success" ></i> Mis Solicitudes</h2>
                    @if($recentRequests->count() > 0)
                        <a href="{{ route('client.requests.index') }}">Ver todas <i class="bi bi-arrow-right"></i></a>
                    @endif
                </div>
                <div class="section-body p-0">
                    @if($recentRequests->count() > 0)
                        @foreach($recentRequests as $request)
                            <a href="{{ route('client.requests.show', $request) }}" class="request-item text-decoration-none">
                                @if($request->product && $request->product->mainImage)
                                    <img src="{{ image_url($request->product->mainImage->path) }}" alt="{{ $request->product->name ?? 'Producto' }}" onerror="this.src='{{ asset('images/no-image.png') }}';">
                                @else
                                    <div class="request-img-placeholder" style="width:50px;height:50px;background:#f1f5f9;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                                <div class="request-info">
                                    <h4>{{ $request->product->name ?? 'Producto no disponible' }}</h4>
                                    <small><i class="bi bi-calendar3 me-1"></i>{{ $request->created_at->format('d/m/Y') }}</small>
                                </div>
                                <span class="status-badge {{ $request->status }}">
                                    {{ $request->status_label }}
                                </span>
                            </a>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="bi bi-envelope-open"></i>
                            <p>No tienes solicitudes aún.<br><a href="{{ route('catalog.index') }}">Explora productos y solicita información</a></p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
