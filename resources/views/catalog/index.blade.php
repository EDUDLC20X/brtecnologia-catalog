@extends('layouts.app')

@section('title', request('search') ? 'Buscar: '.request('search') : 'Catálogo de Productos')

@section('seo')
<x-seo-meta 
    :title="request('search') ? 'Buscar: '.request('search').' | Catálogo' : 'Catálogo de Productos'"
    description="Explora nuestro catálogo completo de herramientas eléctricas, equipos industriales y tecnología."
    keywords="catálogo, herramientas, equipos industriales, tecnología, productos B&R"
/>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('css/catalog.css') }}">
@endsection

@section('content')
<div class="catalog-page">
    
    {{-- Barra de Categorías Superior --}}
    <div class="categories-bar">
        <div class="container-xl">
            <div class="categories-bar-inner">
                <a href="{{ route('catalog.index') }}" class="category-bar-item {{ !request('categories') ? 'active' : '' }}">
                    <i class="bi bi-grid-3x3-gap"></i>
                    <span>Todos</span>
                </a>
                @foreach($categories->take(8) as $category)
                    <a href="{{ route('catalog.index', ['categories' => [$category->id]]) }}" 
                       class="category-bar-item {{ in_array($category->id, (array)request('categories', [])) ? 'active' : '' }}">
                        <i class="bi bi-box-seam"></i>
                        <span>{{ $category->name }}</span>
                    </a>
                @endforeach
                @if($categories->count() > 8)
                    <div class="dropdown">
                        <a href="#" class="category-bar-item dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots"></i>
                            <span>Más</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @foreach($categories->skip(8) as $category)
                                <li>
                                    <a class="dropdown-item" href="{{ route('catalog.index', ['categories' => [$category->id]]) }}">
                                        {{ $category->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Barra de Búsqueda y Filtros --}}
    <div class="search-filter-bar">
        <div class="container-xl">
            <div class="search-filter-inner">
                <form action="{{ route('catalog.index') }}" method="GET" class="search-form">
                    <div class="input-group">
                        <input type="search" name="search" class="form-control" 
                               placeholder="¿Qué estás buscando?" 
                               value="{{ request('search') }}" 
                               aria-label="Buscar">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
                <div class="filter-buttons">
                    <button class="btn btn-primary" type="button" 
                            data-bs-toggle="offcanvas" data-bs-target="#filtersOffcanvas">
                        <i class="bi bi-funnel"></i> <span>Filtros</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container-xl py-4">
        @if(isset($viewMode) && $viewMode === 'by_category')
            {{-- ========================================= --}}
            {{-- VISTA POR CATEGORÍAS (Sin filtros activos) --}}
            {{-- ========================================= --}}
            
            {{-- Sección: Ofertas Especiales --}}
            @if(isset($productsOnSale) && $productsOnSale->count() > 0)
                <section class="catalog-section offers-section mb-5">
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="bi bi-percent text-danger"></i>
                            OFERTAS ESPECIALES
                        </h2>
                        <a href="{{ route('catalog.index', ['on_sale' => 1]) }}" class="section-link">
                            Ver todas <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                    
                    <div class="products-carousel-wrapper">
                        <button class="carousel-nav carousel-prev" data-target="carousel-offers">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        
                        <div class="products-carousel" id="carousel-offers">
                            @foreach($productsOnSale as $product)
                                <div class="product-card-catalog on-sale">
                                    <a href="{{ route('catalog.show', $product) }}" class="product-card-link">
                                        <div class="sale-badge">
                                            <span>-{{ $product->discount_percentage }}%</span>
                                        </div>
                                        <div class="product-image">
                                            @if($product->mainImage)
                                                <img src="{{ image_url($product->mainImage->path) }}" 
                                                     alt="{{ $product->name }}"
                                                     loading="lazy">
                                            @else
                                                <div class="no-image">
                                                    <i class="bi bi-image"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="product-info">
                                            <h4 class="product-name">{{ $product->name }}</h4>
                                            <p class="product-price-original">${{ number_format($product->price_base, 2) }}</p>
                                            <p class="product-price sale-price">${{ number_format($product->sale_price, 2) }}</p>
                                        </div>
                                    </a>
                                    <a href="{{ route('catalog.show', $product) }}" class="btn-view-product">
                                        <i class="bi bi-eye"></i> Ver Oferta
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        
                        <button class="carousel-nav carousel-next" data-target="carousel-offers">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </section>
            @endif
            
            {{-- Sección: Categorías Más Visitadas --}}
            @if($categories->count() > 0)
                <section class="catalog-section mb-5">
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="bi bi-grid-3x3-gap-fill"></i>
                            Categorías
                        </h2>
                    </div>
                    <div class="categories-grid">
                        @foreach($categories as $category)
                            <a href="{{ route('catalog.index', ['categories' => [$category->id]]) }}" class="category-card-mini">
                                <div class="category-icon">
                                    <i class="bi bi-box-seam"></i>
                                </div>
                                <span class="category-name">{{ $category->name }}</span>
                                <span class="category-count">{{ $category->products_count }} productos</span>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Secciones de Productos por Categoría --}}
            @if(isset($categoriesWithProducts))
                @foreach($categoriesWithProducts as $category)
                    <section class="catalog-section mb-5">
                        <div class="section-header">
                            <h2 class="section-title">{{ strtoupper($category->name) }}</h2>
                            <a href="{{ route('catalog.index', ['categories' => [$category->id]]) }}" class="section-link">
                                Ver todos <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                        
                        <div class="products-carousel-wrapper">
                            <button class="carousel-nav carousel-prev" data-target="carousel-{{ $category->id }}">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            
                            <div class="products-carousel" id="carousel-{{ $category->id }}">
                                @foreach($category->featuredProducts as $product)
                                    <div class="product-card-catalog {{ $product->isCurrentlyOnSale() ? 'on-sale' : '' }}">
                                        <a href="{{ route('catalog.show', $product) }}" class="product-card-link">
                                            @if($product->isCurrentlyOnSale())
                                                <div class="sale-badge">
                                                    <span>-{{ $product->discount_percentage }}%</span>
                                                </div>
                                            @endif
                                            <div class="product-image">
                                                @if($product->mainImage)
                                                    <img src="{{ image_url($product->mainImage->path) }}" 
                                                         alt="{{ $product->name }}"
                                                         loading="lazy">
                                                @else
                                                    <div class="no-image">
                                                        <i class="bi bi-image"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="product-info">
                                                <h4 class="product-name">{{ $product->name }}</h4>
                                                @if($product->sku_code)
                                                    <p class="product-sku">Código: {{ $product->sku_code }}</p>
                                                @endif
                                                <p class="product-price-label">Precio</p>
                                                @if($product->isCurrentlyOnSale())
                                                    <p class="product-price-original">${{ number_format($product->price_base, 2) }}</p>
                                                    <p class="product-price sale-price">${{ number_format($product->sale_price, 2) }}</p>
                                                @else
                                                    <p class="product-price">${{ number_format($product->price_base, 2) }}</p>
                                                @endif
                                            </div>
                                        </a>
                                        <a href="{{ route('catalog.show', $product) }}" class="btn-view-product">
                                            <i class="bi bi-eye"></i> Ver Detalles
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                            
                            <button class="carousel-nav carousel-next" data-target="carousel-{{ $category->id }}">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                    </section>
                @endforeach
            @endif

        @else
            {{-- ========================================= --}}
            {{-- VISTA FILTRADA (Con filtros activos) --}}
            {{-- ========================================= --}}
            
            {{-- Indicador de filtros activos --}}
            @if(request('search') || request('categories'))
                <div class="active-filters mb-4">
                    <span class="filters-label">Filtros activos:</span>
                    @if(request('search'))
                        <span class="filter-tag">
                            Búsqueda: "{{ request('search') }}"
                            <a href="{{ route('catalog.index', array_diff_key(request()->query(), ['search' => ''])) }}" class="remove-filter">
                                <i class="bi bi-x"></i>
                            </a>
                        </span>
                    @endif
                    @if(request('categories'))
                        @foreach((array)request('categories') as $catId)
                            @php $cat = $categories->firstWhere('id', $catId); @endphp
                            @if($cat)
                                <span class="filter-tag">
                                    {{ $cat->name }}
                                    <a href="{{ route('catalog.index', array_merge(request()->except('categories'), ['categories' => array_diff((array)request('categories'), [$catId])])) }}" class="remove-filter">
                                        <i class="bi bi-x"></i>
                                    </a>
                                </span>
                            @endif
                        @endforeach
                    @endif
                    <a href="{{ route('catalog.index') }}" class="clear-all-filters">Limpiar todo</a>
                </div>
            @endif

            <div id="catalogContent">
                @include('catalog.partials.products_grid')
            </div>
        @endif
    </div>
</div>

{{-- Offcanvas de Filtros --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="filtersOffcanvas" aria-labelledby="filtersOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="filtersOffcanvasLabel">
            <i class="bi bi-funnel me-2"></i>Filtros
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
    </div>
    <div class="offcanvas-body">
        <x-filters-sidebar :categories="$categories" :priceRange="$priceRange" :brands="$brands" />
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Funcionalidad de carrusel de productos
    document.querySelectorAll('.carousel-nav').forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.dataset.target;
            const carousel = document.getElementById(targetId);
            if (!carousel) return;
            
            const scrollAmount = 280; // Ancho aproximado de una tarjeta
            const direction = this.classList.contains('carousel-prev') ? -1 : 1;
            
            carousel.scrollBy({
                left: scrollAmount * direction,
                behavior: 'smooth'
            });
        });
    });

    // Actualizar visibilidad de botones de navegación
    function updateCarouselNavigation() {
        document.querySelectorAll('.products-carousel').forEach(carousel => {
            const wrapper = carousel.closest('.products-carousel-wrapper');
            if (!wrapper) return;
            
            const prevBtn = wrapper.querySelector('.carousel-prev');
            const nextBtn = wrapper.querySelector('.carousel-next');
            
            if (prevBtn) {
                prevBtn.style.opacity = carousel.scrollLeft <= 0 ? '0.3' : '1';
            }
            if (nextBtn) {
                const maxScroll = carousel.scrollWidth - carousel.clientWidth;
                nextBtn.style.opacity = carousel.scrollLeft >= maxScroll - 5 ? '0.3' : '1';
            }
        });
    }

    document.querySelectorAll('.products-carousel').forEach(carousel => {
        carousel.addEventListener('scroll', updateCarouselNavigation);
    });

    updateCarouselNavigation();
});
</script>
@endpush
