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
    <div class="promo-banner text-center py-2" style="background-color: {{ $banners['banner.promo.bg_color'] ?? '#1a4d8c' }};">
        <a href="{{ $banners['banner.promo.link'] ?? '/productos' }}" class="text-white text-decoration-none">
            <i class="bi bi-megaphone me-2"></i>{{ $banners['banner.promo.text'] ?? '' }}
        </a>
    </div>
@endif

<!-- HERO SECTION -->
<section class="hero-section">
    <div class="hero-inner container-fluid px-0">
        <div class="hero-content container-xl d-flex flex-column justify-content-center align-items-center text-center">
            @php
                // Imagen del Hero - si el admin sube una, reemplaza al logo
                $heroImage = $content['home.hero.image'] ?? null;
                // Si no hay imagen del hero, usar el logo
                $logoPath = $global['global.logo'] ?? null;
                
                // Determinar qué imagen mostrar: Hero personalizada > Logo personalizado > Logo por defecto
                if ($heroImage) {
                    $displayImage = content_image_url($heroImage);
                } elseif ($logoPath) {
                    $displayImage = content_image_url($logoPath, 'images/logo-white.png');
                } else {
                    $displayImage = asset('images/logo-white.png');
                }
            @endphp
            
            {{-- Imagen principal del Hero (puede ser logo o imagen personalizada) --}}
            <img src="{{ $displayImage }}" 
                 style="max-height: 300px; max-width: 400px; width: auto; height: auto; object-fit: contain;"    
                 alt="{{ $global['global.company_name'] ?? 'B&R Tecnología' }}" 
                 class="br-logo mb-3"
                 onerror="this.onerror=null; this.src='{{ asset('images/logo-white.png') }}'">

            <h1 class="hero-title">{{ $content['home.hero.title'] ?? 'Herramientas eléctricas, equipos industriales y tecnología' }}</h1>
            <p class="lead hero-sub">{{ $content['home.hero.subtitle'] ?? 'Su herramienta de trabajo en las mejores manos' }}</p>

            <form action="{{ route('catalog.index') }}" method="GET" class="hero-search w-100" style="max-width:720px;">
                <input type="search" name="q" placeholder="{{ $content['home.hero.search_placeholder'] ?? 'Buscar taladro, multímetro, robot, ...' }}" aria-label="Buscar" />
                <button type="submit"><i class="bi bi-search"></i></button>
            </form>

            <div class="hero-buttons d-flex gap-2 mt-3" style="max-width:420px; width:100%;">
                <a href="{{ route('catalog.index') }}" class="btn-primary-br flex-fill text-center">
                    <i class="bi bi-shop"></i> Explorar Catálogo
                </a>
                <a href="{{ route('contact') }}" class="btn-secondary-br flex-fill text-center">
                    <i class="bi bi-telephone"></i> Contáctanos
                </a>
            </div>
        </div>
    </div>
</section>

<!-- CATEGORIES SECTION -->
<section class="section">
    <div class="container-xl">
        <div class="section-title">
            <div class="section-divider"></div>
            <h2>{{ $content['home.categories.title'] ?? 'Nuestras Categorías' }}</h2>
            <p>{{ $content['home.categories.subtitle'] ?? 'Encuentra todo lo que necesitas para tu negocio' }}</p>
        </div>
        
        <div class="row g-4">
            @foreach(App\Models\Category::limit(6)->get() as $category)
                <div class="col-md-4 col-lg-2">
                    <a href="{{ route('catalog.index', ['category' => $category->id]) }}" class="text-decoration-none">
                        <div class="category-card">
                            <i class="bi bi-box"></i>
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
<section class="section" style="background-color: white;">
    <div class="container-xl">
        <div class="section-title">
            <div class="section-divider"></div>
            <h2>{{ $content['home.featured.title'] ?? 'Productos Destacados' }}</h2>
            <p>{{ $content['home.featured.subtitle'] ?? 'Nuestros mejores productos seleccionados para ti' }}</p>
        </div>

        <div class="row g-4">
            @forelse(App\Models\Product::with(['mainImage','category'])->where('is_active',true)->limit(8)->get() as $product)
                <div class="col-md-6 col-lg-3">
                    <div class="product-card">
                        <div class="product-image">
                            @if(isset($product->mainImage) && $product->mainImage && $product->mainImage->path)
                                <img src="{{ image_url($product->mainImage->path) }}" alt="{{ $product->name }}" loading="lazy" onerror="this.onerror=null; this.parentElement.innerHTML='<div style=\'color: #ccc; font-size: 3rem;\'><i class=\'bi bi-image\'></i></div>';">
                            @elseif($product->images && $product->images->count() && $product->images->first()->path)
                                <img src="{{ image_url($product->images->first()->path) }}" alt="{{ $product->name }}" loading="lazy" onerror="this.onerror=null; this.parentElement.innerHTML='<div style=\'color: #ccc; font-size: 3rem;\'><i class=\'bi bi-image\'></i></div>';">
                            @else
                                <div style="color: #ccc; font-size: 3rem;"><i class="bi bi-image"></i></div>
                            @endif
                            @if($product->stock_available < 5 && $product->stock_available > 0)
                                <span class="product-badge" style="background-color: var(--br-accent);">Limitado</span>
                            @elseif($product->stock_available == 0)
                                <span class="product-badge" style="background-color: #999;">Agotado</span>
                            @endif
                        </div>
                        
                        <div class="product-info">
                            <div class="product-category">{{ $product->category->name ?? 'Sin Categoría' }}</div>
                            <h6 class="product-name">{{ $product->name }}</h6>
                            <p class="product-description">{{ \Illuminate\Support\Str::limit($product->description, 60) }}</p>
                            
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
                        No hay productos disponibles en este momento.
                    </div>
                </div>
            @endforelse
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('catalog.index') }}" class="btn-primary-br">
                <i class="bi bi-arrow-right"></i> Ver Todos los Productos
            </a>
        </div>
    </div>
</section>

<!-- INFO BOXES SECTION -->
<section class="section">
    <div class="container-xl">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="offer-banner" style="background: linear-gradient(135deg, rgba(13, 42, 79, 0.8) 0%, rgba(12, 95, 108, 0.8) 100%); border: 1px solid var(--br-border); padding: 2rem;">
                    <i class="bi bi-truck" style="font-size: 2.5rem; margin-bottom: 1rem;"></i>
                    <h5 style="color: white;">Envío Rápido</h5>
                    <p style="color: rgba(255, 255, 255, 0.8);">Entrega en 24-48 horas en el área metropolitana</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="offer-banner" style="background: linear-gradient(135deg, rgba(13, 42, 79, 0.8) 0%, rgba(12, 95, 108, 0.8) 100%); border: 1px solid var(--br-border); padding: 2rem;">
                    <i class="bi bi-shield-check" style="font-size: 2.5rem; margin-bottom: 1rem;"></i>
                    <h5 style="color: white;">Productos Garantizados</h5>
                    <p style="color: rgba(255, 255, 255, 0.8);">Garantía oficial del fabricante en todos nuestros productos</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="offer-banner" style="background: linear-gradient(135deg, rgba(13, 42, 79, 0.8) 0%, rgba(12, 95, 108, 0.8) 100%); border: 1px solid var(--br-border); padding: 2rem;">
                    <i class="bi bi-headset" style="font-size: 2.5rem; margin-bottom: 1rem;"></i>
                    <h5 style="color: white;">Soporte 24/7</h5>
                    <p style="color: rgba(255, 255, 255, 0.8);">Estamos disponibles para resolver tus dudas en cualquier momento</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
