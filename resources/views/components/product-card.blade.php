@props(['product'])

<div class="card h-100 shadow-sm border-0 overflow-hidden hover-card" style="transition: all 0.3s ease;">
    <!-- Image Container -->
    <div class="position-relative bg-light" style="height: 220px; overflow: hidden;">
        @if(isset($product->mainImage) && $product->mainImage && $product->mainImage->path)
            <img src="{{ image_url($product->mainImage->path) }}" alt="{{ $product->name }}" 
                 class="img-fluid h-100 w-100" style="object-fit: cover; transition: transform 0.3s ease;"
                 onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'d-flex align-items-center justify-content-center h-100 text-muted\'><i class=\'bi bi-image\' style=\'font-size: 3rem;\'></i></div>';">
        @elseif($product->images && $product->images->count() && $product->images->first()->path)
            <img src="{{ image_url($product->images->first()->path) }}" alt="{{ $product->name }}" 
                 class="img-fluid h-100 w-100" style="object-fit: cover; transition: transform 0.3s ease;"
                 onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'d-flex align-items-center justify-content-center h-100 text-muted\'><i class=\'bi bi-image\' style=\'font-size: 3rem;\'></i></div>';">
        @else
            <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                <i class="bi bi-image" style="font-size: 3rem;"></i>
            </div>
        @endif

        <!-- Stock Badge -->
        <div class="position-absolute top-2 start-2">
            @if($product->stock_available > 0)
                <span class="badge bg-success">Stock: {{ $product->stock_available }}</span>
            @else
                <span class="badge bg-danger">Sin stock</span>
            @endif
        </div>

        <!-- Discount Badge (if applicable) -->
        @if($product->discount_percentage ?? false)
            <div class="position-absolute top-2 end-2">
                <span class="badge bg-danger">-{{ $product->discount_percentage }}%</span>
            </div>
        @endif
    </div>

    <!-- Card Body -->
    <div class="card-body d-flex flex-column">
        <!-- Category -->
        @if($product->category)
            <div class="mb-2">
                <span class="badge bg-info">{{ $product->category->name }}</span>
            </div>
        @endif

        <!-- Product Name -->
        <h6 class="card-title fw-bold text-dark line-clamp-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
            {{ $product->name }}
        </h6>

        <!-- Description -->
        <p class="card-text text-muted small mb-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
            {{ \Illuminate\Support\Str::limit($product->description, 80) }}
        </p>

        <!-- SKU -->
        <p class="text-muted small mb-3">
            <code>{{ $product->sku_code }}</code>
        </p>

        <!-- Spacer -->
        <div class="flex-grow-1"></div>

        <!-- Rating (if available) -->
        @if($product->average_rating ?? false)
            <div class="mb-2">
                <small class="text-warning">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= round($product->average_rating))
                            <i class="bi bi-star-fill"></i>
                        @else
                            <i class="bi bi-star"></i>
                        @endif
                    @endfor
                    ({{ number_format($product->average_rating, 1) }})
                </small>
            </div>
        @endif

        <!-- Price -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <div class="h5 text-primary fw-bold mb-0">${{ number_format($product->price_base, 2) }}</div>
                @if($product->price_original > $product->price_base)
                    <small class="text-muted"><s>${{ number_format($product->price_original, 2) }}</s></small>
                @endif
            </div>
        </div>
    </div>

    <!-- Card Footer -->
    <div class="card-footer bg-white border-top-0 pt-0">
        <div class="d-grid gap-2">
            <a href="{{ route('catalog.show', $product) }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-eye"></i> Ver detalles
            </a>
            @if(!auth()->check() || !auth()->user()->isAdmin())
                @if($product->stock_available > 0)
                    <button type="button" class="btn btn-primary btn-sm add-to-quote-btn" 
                            data-url="{{ route('quote.add', $product) }}">
                        <i class="bi bi-cart-plus"></i> Agregar a Cotización
                    </button>
                @else
                    <button type="button" class="btn btn-secondary btn-sm" disabled>
                        <i class="bi bi-exclamation-circle"></i> Sin stock
                    </button>
                @endif
            @endif
        </div>
    </div>
</div>

<style>
    .hover-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.2) !important;
    }

    .hover-card:hover img {
        transform: scale(1.05);
    }
</style>
