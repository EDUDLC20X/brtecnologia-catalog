@extends('layouts.app')

@section('title', $product->name)

@section('seo')
@php
    $productImage = $product->mainImage?->url ?? ($product->images->first()?->url ?? asset('images/no-image.png'));
    $availability = ($product->stock_available ?? 0) > 0 ? 'InStock' : 'OutOfStock';
@endphp
<x-seo-meta 
    :title="$product->name"
    :description="Str::limit(strip_tags($product->description), 160)"
    :keywords="$product->category?->name . ', ' . $product->sku_code . ', herramientas, B&R'"
    :image="$productImage"
    type="product"
    :price="$product->price_base"
    currency="USD"
    :availability="$availability"
/>
@endsection

@section('content')
<div class="container py-4">
    <div class="row g-4">
        <div class="col-12">
            <a href="{{ route('catalog.index') }}" class="text-muted small"><i class="bi bi-arrow-left"></i> Volver al catálogo</a>
        </div>

        <div class="col-lg-6">
            <div class="product-gallery">
                @php
                    // Only include images that actually exist in public storage
                    $imgs = $product->images->filter(function($img){
                        $url = $img->url ?? '';
                        return $url && file_exists(public_path(ltrim($url, '/')));
                    })->values();
                @endphp
                @if($imgs->count())
                    <div id="productCarousel" class="carousel slide carousel-fade" data-bs-ride="false">
                        <div class="carousel-inner">
                            @foreach($imgs as $idx => $img)
                                <div class="carousel-item {{ $idx===0 ? 'active' : '' }}">
                                    <img src="{{ $img->url }}" class="d-block w-100 img-fluid" loading="lazy" alt="{{ $product->name }}">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="product-thumbs d-flex gap-2 mt-3">
                        @foreach($imgs as $idx => $img)
                            <button class="thumb-btn btn p-0 border {{ $idx===0 ? 'active' : '' }}" data-bs-target="#productCarousel" data-bs-slide-to="{{ $idx }}" aria-label="Ir a imagen {{ $idx+1 }}">
                                <img src="{{ $img->url }}" alt="thumb-{{ $idx }}" style="height:64px; object-fit:contain;" loading="lazy">
                            </button>
                        @endforeach
                    </div>
                @else
                    <div class="product-main-image mb-3">
                        <div class="br-no-image p-5 text-center">Sin imagen</div>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-6">
            <h1 class="mb-2" style="color:var(--br-blue);">{{ $product->name }}</h1>
            
            {{-- Precios con oferta --}}
            <div class="mb-3">
                @if($product->isCurrentlyOnSale())
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <span class="badge bg-danger fs-6 px-3 py-2">
                            <i class="bi bi-percent me-1"></i>-{{ $product->discount_percentage }}% OFERTA
                        </span>
                        @if($product->sale_ends_at)
                            <small class="text-muted">
                                <i class="bi bi-clock me-1"></i>Termina: {{ $product->sale_ends_at->format('d/m/Y H:i') }}
                            </small>
                        @endif
                    </div>
                    <div class="mt-2">
                        <span class="text-muted text-decoration-line-through fs-5">${{ number_format($product->price_base, 2) }}</span>
                        <strong class="h3 text-danger ms-2">${{ number_format($product->sale_price, 2) }}</strong>
                        <small class="text-success ms-2">¡Ahorras ${{ number_format($product->price_base - $product->sale_price, 2) }}!</small>
                    </div>
                @else
                    <strong class="h4 text-dark">${{ number_format($product->price_base, 2) }}</strong>
                @endif
            </div>
            
            <p class="text-muted">{{ Str::limit($product->description, 220) }}</p>

            <!-- Stock Status -->
            <div class="mb-3">
                @if(($product->stock_available ?? 0) > 0)
                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Disponible</span>
                    @if($product->stock_available < 5)
                        <span class="badge bg-warning text-dark ms-1">¡Últimas {{ $product->stock_available }} unidades!</span>
                    @endif
                @else
                    <span class="badge bg-secondary"><i class="bi bi-x-circle me-1"></i>Agotado temporalmente</span>
                @endif
            </div>

            <div class="d-flex gap-2 mb-3">
                <button class="btn btn-primary btn-lg" type="button" data-bs-toggle="modal" data-bs-target="#requestModal">
                    <i class="bi bi-envelope me-1"></i>Solicitar Información
                </button>
                <a href="#specs" class="btn btn-outline-secondary">Ver especificaciones</a>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title mb-2">Especificaciones técnicas</h5>
                    @if(!empty($product->technical_specs))
                        <div class="small text-muted">{!! nl2br(e($product->technical_specs)) !!}</div>
                    @else
                        <div class="small text-muted">No hay especificaciones disponibles.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 mt-4" id="specs">
            <h4>Descripción completa</h4>
            <p class="text-muted">{!! nl2br(e($product->description)) !!}</p>
        </div>

        <div class="col-12 mt-4">
            <h4>Productos relacionados</h4>
            <div class="products-grid mt-3">
                @foreach($relatedProducts as $rp)
                    <div class="br-product-card p-2">
                        <a href="{{ route('catalog.show', $rp) }}" class="text-decoration-none text-reset">
                            <div class="br-product-media text-center p-3" style="height:140px;">
                                                @php
                                                    $rsrc = $rp->mainImage->url ?? null;
                                                    $rfs = $rsrc ? public_path(ltrim($rsrc, '/')) : null;
                                                @endphp
                                                @if($rsrc && file_exists($rfs))
                                                    <img src="{{ $rsrc }}" alt="{{ $rp->name }}" style="max-height:120px; object-fit:contain;">
                                                @endif
                            </div>
                            <div class="br-product-body p-2">
                                <div class="br-product-title small">{{ Str::limit($rp->name, 50) }}</div>
                                <div class="br-product-price small mt-1">${{ number_format($rp->price_base,2) }}</div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    // Thumbnail buttons control the Bootstrap carousel and active state
    var carouselEl = document.getElementById('productCarousel');
    if (carouselEl) {
        var carousel = new bootstrap.Carousel(carouselEl, { interval: false, ride: false });

        // On slide, update active thumbnail
        carouselEl.addEventListener('slide.bs.carousel', function (e) {
            var newIndex = e.to;
            document.querySelectorAll('.thumb-btn').forEach(function(b, i){
                b.classList.toggle('active', i === newIndex);
            });
        });

        // Make thumbnails navigate the carousel
        document.querySelectorAll('.thumb-btn').forEach(function(btn){
            btn.addEventListener('click', function(e){
                var idx = parseInt(btn.getAttribute('data-bs-slide-to')) || 0;
                carousel.to(idx);
            });
        });

        // Click on main image opens the lightbox modal and syncs to the same slide
        carouselEl.querySelectorAll('.carousel-item img').forEach(function(img, i){
            img.style.cursor = 'zoom-in';
            img.addEventListener('click', function(){
                var lb = new bootstrap.Modal(document.getElementById('productLightbox'));
                // set lightbox carousel to same index
                var lbCarousel = document.getElementById('productLightboxCarousel');
                if (lbCarousel) {
                    var lbCar = new bootstrap.Carousel(lbCarousel, { interval: false, ride: false });
                    // show modal first then move to slide (delay briefly)
                    lb.show();
                    setTimeout(function(){ lbCar.to(i); }, 50);
                } else {
                    lb.show();
                }
            });
        });
    }

    // Request modal AJAX submission
    var requestForm = document.getElementById('requestForm');
    if (requestForm) {
        requestForm.addEventListener('submit', function(e){
            e.preventDefault();
            var btn = this.querySelector('button[type=submit]');
            var data = new FormData(this);
            btn.disabled = true;
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: data
            }).then(function(res){
                return res.json().catch(() => null);
            }).then(function(json){
                btn.disabled = false;
                var msg = document.getElementById('requestMessage');
                if (json && json.success) {
                    msg.className = 'alert alert-success';
                    msg.innerText = json.message || 'Solicitud enviada. Gracias.';
                    requestForm.reset();
                    setTimeout(function(){ var m = bootstrap.Modal.getInstance(document.getElementById('requestModal')); if(m) m.hide(); }, 900);
                } else {
                    msg.className = 'alert alert-danger';
                    msg.innerText = (json && json.message) ? json.message : 'Ocurrió un error al enviar la solicitud.';
                }
            }).catch(function(){
                btn.disabled = false;
                var msg = document.getElementById('requestMessage');
                msg.className = 'alert alert-danger';
                msg.innerText = 'Ocurrió un error de red.';
            });
        });
    }
});
</script>
@endpush

@endsection

<!-- Lightbox modal for gallery -->
<div class="modal fade" id="productLightbox" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen modal-dialog-centered">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body p-0">
                <div id="productLightboxCarousel" class="carousel slide carousel-fade" data-bs-ride="false">
                    <div class="carousel-inner">
                                @foreach($imgs as $idx => $img)
                                    <div class="carousel-item {{ $idx===0 ? 'active' : '' }}">
                                        <img src="{{ $img->url }}" class="d-block w-100 img-fluid" style="object-fit:contain; max-height:90vh;" loading="lazy" alt="">
                                    </div>
                                @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#productLightboxCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#productLightboxCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Request modal -->
<div class="modal fade" id="requestModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Solicitar producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div id="requestMessage" role="status" class="mb-3"></div>
                <form id="requestForm" action="{{ route('contact.send') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input class="form-control" type="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mensaje</label>
                        <textarea class="form-control" name="message" rows="4">Estoy interesado en el producto: {{ $product->name }}</textarea>
                    </div>
                    <div class="d-flex justify-content-end pt-2">
                        <button class="btn btn-secondary me-2" type="button" data-bs-dismiss="modal">Cerrar</button>
                        <button class="btn btn-primary" type="submit">Enviar solicitud</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
