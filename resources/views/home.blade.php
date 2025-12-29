@extends('layouts.app')

@section('title', \App\Models\SiteContent::get('global.company_name', 'B&R Tecnología') . ' - Tu Tienda de Tecnología')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endsection

@section('content')

@php
    // Cargar contenido dinámico para esta página
    $content = \App\Models\SiteContent::getSection('home');
    $global = \App\Models\SiteContent::getSection('global');
    $banners = \App\Models\SiteContent::getSection('banners');
@endphp

<!-- BANNER PROMOCIONAL (si está habilitado) -->
@if(($banners['banner.promo.enabled'] ?? '0') == '1')
    <div class="promo-banner-modern">
        <div class="container-xl">
            <a href="{{ $banners['banner.promo.link'] ?? '/productos' }}" class="promo-link">
                <i class="bi bi-lightning-charge-fill me-2"></i>
                <span>{{ $banners['banner.promo.text'] ?? '' }}</span>
                <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
@endif

<!-- HERO SECTION - DISEÑO MODERNO -->
<section class="hero-section">
    <div class="hero-inner container-fluid px-0">
        <div class="hero-content container-xl d-flex flex-column justify-content-center align-items-center text-center">
            @php
                $heroImage = $content['home.hero.image'] ?? null;
                $logoPath = $global['global.logo'] ?? null;
                
                if ($heroImage) {
                    $displayImage = content_image_url($heroImage);
                } elseif ($logoPath) {
                    $displayImage = content_image_url($logoPath, 'images/logo-white.png');
                } else {
                    $displayImage = asset('images/logo-white.png');
                }
            @endphp
            
            {{-- Logo/Imagen del Hero --}}
            <img src="{{ $displayImage }}" 
                 style="max-height: 280px; max-width: 550px; width: auto; height: auto; object-fit: contain;"    
                 alt="{{ $global['global.company_name'] ?? 'B&R Tecnología' }}" 
                 class="hero-logo mb-4"
                 onerror="this.onerror=null; this.src='{{ asset('images/logo-white.png') }}'">

            <h1 class="hero-title">{{ $content['home.hero.title'] ?? 'Equipos de última generación y tecnología avanzada' }}</h1>
            <p class="lead hero-sub">{{ $content['home.hero.subtitle'] ?? 'Su equipo de trabajo en las mejores manos' }}</p>

            <form action="{{ route('catalog.index') }}" method="GET" class="hero-search w-100" style="max-width:640px;">
                <input type="search" name="q" placeholder="{{ $content['home.hero.search_placeholder'] ?? 'Buscar procesador, tarjeta gráfica, laptop, ...' }}" aria-label="Buscar" />
                <button type="submit"><i class="bi bi-search"></i></button>
            </form>

            <div class="hero-buttons d-flex gap-3 mt-3">
                <a href="{{ route('catalog.index') }}" class="btn-primary-br">
                    <i class="bi bi-grid-3x3-gap"></i> Explorar Catálogo
                </a>
                <a href="{{ route('contact') }}" class="btn-secondary-br">
                    <i class="bi bi-chat-dots"></i> Contáctanos
                </a>
            </div>
        </div>
    </div>
</section>

<!-- CATEGORIES SECTION -->
<section class="section" style="background: var(--gray-50);">
    <div class="container-xl">
        <div class="section-title">
            <div class="section-divider"></div>
            <h2>{{ $content['home.categories.title'] ?? 'Nuestras Categorías' }}</h2>
            <p>{{ $content['home.categories.subtitle'] ?? 'Encuentra todo lo que necesitas para tu negocio' }}</p>
        </div>
        
        <div class="row g-4">
            @foreach(App\Models\Category::limit(6)->get() as $category)
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="{{ route('catalog.index', ['categories' => [$category->id]]) }}" class="text-decoration-none">
                        <div class="category-card">
                            <i class="bi {{ $category->icon ?? 'bi-box' }}"></i>
                            <h5>{{ $category->name }}</h5>
                            <p>{{ $category->products()->count() }} productos</p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- FEATURED PRODUCTS SECTION -->
<section class="section" style="background: var(--white);">
    <div class="container-xl">
        <div class="section-title">
            <div class="section-divider"></div>
            <h2>{{ $content['home.featured.title'] ?? 'Productos Destacados' }}</h2>
            <p>{{ $content['home.featured.subtitle'] ?? 'Nuestros mejores productos seleccionados para ti' }}</p>
        </div>

        <div class="row g-4">
            @forelse(App\Models\Product::with(['mainImage','category'])->where('is_active',true)->limit(8)->get() as $product)
                <div class="col-6 col-md-6 col-lg-3">
                    <div class="product-card h-100">
                        <div class="product-image">
                            @if(isset($product->mainImage) && $product->mainImage && $product->mainImage->path)
                                <img src="{{ image_url($product->mainImage->path) }}" alt="{{ $product->name }}" loading="lazy" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'no-image-placeholder\'><i class=\'bi bi-image\'></i></div>';">
                            @elseif($product->images && $product->images->count() && $product->images->first()->path)
                                <img src="{{ image_url($product->images->first()->path) }}" alt="{{ $product->name }}" loading="lazy" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'no-image-placeholder\'><i class=\'bi bi-image\'></i></div>';">
                            @else
                                <div class="no-image-placeholder"><i class="bi bi-image"></i></div>
                            @endif
                            @if($product->stock_available < 5 && $product->stock_available > 0)
                                <span class="product-badge" style="background: linear-gradient(135deg, #f59e0b, #d97706);">Limitado</span>
                            @elseif($product->stock_available == 0)
                                <span class="product-badge" style="background: linear-gradient(135deg, #6b7280, #4b5563);">Agotado</span>
                            @endif
                        </div>
                        
                        <div class="product-info">
                            <div class="product-category">{{ $product->category->name ?? 'Sin Categoría' }}</div>
                            <h6 class="product-name">{{ $product->name }}</h6>
                            <p class="product-description">{{ \Illuminate\Support\Str::limit($product->description, 55) }}</p>
                            
                            <div class="product-price">
                                <span class="product-price-main">${{ number_format($product->price_base, 2) }}</span>
                            </div>

                            <div class="product-buttons">
                                <a href="{{ route('catalog.show', $product) }}" class="btn-view">
                                    <i class="bi bi-eye"></i> Ver Detalles
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="bi bi-info-circle me-2"></i>No hay productos disponibles en este momento.
                    </div>
                </div>
            @endforelse
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('catalog.index') }}" class="btn btn-primary btn-lg px-5">
                <i class="bi bi-arrow-right me-2"></i> Ver Todos los Productos
            </a>
        </div>
    </div>
</section>

<!-- FEATURES SECTION -->
<section class="section" style="background: var(--gray-50);">
    <div class="container-xl">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-truck"></i>
                    </div>
                    <h5>Envío Rápido</h5>
                    <p>Entrega en 24-48 horas en el área metropolitana</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h5>Productos Garantizados</h5>
                    <p>Garantía oficial del fabricante en todos nuestros productos</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-headset"></i>
                    </div>
                    <h5>Soporte 24/7</h5>
                    <p>Estamos disponibles para resolver tus dudas en cualquier momento</p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Estilos adicionales para el Home */
.promo-banner-modern {
    background: linear-gradient(135deg, #3b82f6 0%, #0ea5e9 100%);
    padding: 0.75rem 0;
}

.promo-banner-modern .promo-link {
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.promo-banner-modern .promo-link:hover {
    transform: scale(1.02);
}

.no-image-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: var(--gray-300);
    font-size: 3rem;
}

/* Feature Cards mejoradas */
.feature-card {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0c4a6e 100%);
    border-radius: 24px;
    padding: 2.5rem 2rem;
    text-align: center;
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.1);
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(15, 23, 42, 0.3);
}

.feature-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(59, 130, 246, 0.15) 0%, transparent 70%);
    pointer-events: none;
}

.feature-card .feature-icon {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    position: relative;
    z-index: 1;
    transition: all 0.3s ease;
}

.feature-card:hover .feature-icon {
    background: rgba(255, 255, 255, 0.15);
    transform: scale(1.1);
}

.feature-card .feature-icon i {
    font-size: 2rem;
    color: #ffffff;
}

.feature-card h5 {
    color: #ffffff;
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
    position: relative;
    z-index: 1;
}

.feature-card p {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.95rem;
    margin: 0;
    line-height: 1.5;
    position: relative;
    z-index: 1;
}
</style>

@endsection
