@if($products->count() > 0)
    <div class="row g-4 mb-5" id="productsGrid">
        @foreach($products as $product)
            @php
                // prepare images array for quick-view usage
                $images = $product->images->map(function($img){ return ['id' => $img->id, 'path' => asset('storage/' . ltrim($img->path, '/'))]; })->toArray();
                $isOnSale = $product->isCurrentlyOnSale();
            @endphp
            <div class="col-6 col-md-4 col-lg-3 product-card" data-product-id="{{ $product->id }}" data-product-images='@json($images)'>
                <article class="br-product-card {{ $isOnSale ? 'on-sale' : '' }}" itemscope itemtype="https://schema.org/Product">
                    @if($isOnSale)
                        <div class="sale-badge-grid">
                            <span>-{{ $product->discount_percentage }}%</span>
                        </div>
                    @endif
                    <div class="br-product-media">
                        @if($product->mainImage)
                            <img 
                                src="{{ asset('storage/' . ltrim($product->mainImage->path, '/')) }}" 
                                alt="{{ $product->name }}"
                                loading="lazy"
                                decoding="async"
                                itemprop="image"
                            >
                        @else
                            <div class="br-no-image">Sin imagen</div>
                        @endif
                    </div>

                    <div class="br-product-body">
                        <h4 class="br-product-title" itemprop="name">{{ $product->name }}</h4>

                        <div class="br-product-price" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                            @if($isOnSale)
                                <span class="original-price"><s>${{ number_format($product->price_base, 2) }}</s></span>
                                <span class="sale-price-text" itemprop="priceCurrency" content="USD">$</span><span class="sale-price-text" itemprop="price" content="{{ $product->sale_price }}">{{ number_format($product->sale_price, 2) }}</span>
                            @else
                                <span itemprop="priceCurrency" content="USD">$</span><span itemprop="price" content="{{ $product->price_base }}">{{ number_format($product->price_base, 2) }}</span>
                            @endif
                            <link itemprop="availability" href="https://schema.org/{{ ($product->stock_available ?? 0) > 0 ? 'InStock' : 'OutOfStock' }}">
                        </div>

                        <div class="br-product-actions">
                            <a href="{{ route('catalog.show', $product) }}" class="btn btn-sm btn-primary w-100" style="font-size: 0.85rem;" itemprop="url">
                                <i class="bi bi-eye"></i> Ver Detalles
                            </a>
                        </div>
                    </div>
                </article>
            </div>
        @endforeach
    </div>

    <nav aria-label="Paginación de productos">
        <div class="d-flex justify-content-center" id="productsPagination">
            {{ $products->appends(request()->query())->links() }}
        </div>
    </nav>
@else
    <div class="alert alert-info text-center py-5">
        <h4>📭 Sin resultados</h4>
        <p class="mb-3">No hay productos que coincidan con los filtros seleccionados.</p>
        <a href="{{ route('catalog') }}" class="btn btn-primary">
            <i class="bi bi-arrow-clockwise"></i> Limpiar Filtros
        </a>
    </div>
@endif
