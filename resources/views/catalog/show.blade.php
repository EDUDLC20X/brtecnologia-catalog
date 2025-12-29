@extends('layouts.app')

@section('title', $product->name)

@section('seo')
@php
    $productImage = $product->mainImage ? image_url($product->mainImage->path) : ($product->images->first() ? image_url($product->images->first()->path) : asset('images/no-image.png'));
    $availability = ($product->stock_available ?? 0) > 0 ? 'InStock' : 'OutOfStock';
@endphp
<x-seo-meta 
    :title="$product->name"
    :description="Str::limit(strip_tags($product->description), 160)"
    :keywords="$product->category?->name . ', ' . $product->sku_code . ', equipos, B&R'"
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
                    // Get all images with valid paths
                    $imgs = $product->images->filter(fn($img) => !empty($img->path))->values();
                @endphp
                @if($imgs->count())
                    <div id="productCarousel" class="carousel slide carousel-fade" data-bs-ride="false">
                        <div class="carousel-inner">
                            @foreach($imgs as $idx => $img)
                                <div class="carousel-item {{ $idx===0 ? 'active' : '' }}">
                                    <img src="{{ image_url($img->path) }}" class="d-block w-100 img-fluid" loading="lazy" alt="{{ $product->name }}" onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}';">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="product-thumbs d-flex gap-2 mt-3">
                        @foreach($imgs as $idx => $img)
                            <button class="thumb-btn btn p-0 border {{ $idx===0 ? 'active' : '' }}" data-bs-target="#productCarousel" data-bs-slide-to="{{ $idx }}" aria-label="Ir a imagen {{ $idx+1 }}">
                                <img src="{{ image_url($img->path) }}" alt="thumb-{{ $idx }}" style="height:64px; object-fit:contain;" loading="lazy" onerror="this.style.display='none';">
                            </button>
                        @endforeach
                    </div>
                @else
                    <div class="product-main-image mb-3">
                        <div class="br-no-image p-5 text-center bg-light rounded">
                            <i class="bi bi-image fs-1 text-muted"></i>
                            <p class="text-muted mt-2 mb-0">Sin imagen</p>
                        </div>
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
            <!-- Short Description -->
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

            <div class="d-flex gap-2 mb-3 flex-wrap">
                <button class="btn btn-primary btn-lg" type="button" data-bs-toggle="modal" data-bs-target="#requestModal">
                    <i class="bi bi-envelope me-1"></i>Solicitar Información
                </button>
                
                {{-- Botón Agregar a Cotización - Solo clientes --}}
                @if(!auth()->check() || !auth()->user()->isAdmin())
                    <button type="button" class="btn btn-success btn-lg add-to-quote-btn" 
                            data-url="{{ route('quote.add', $product) }}">
                        <i class="bi bi-cart-plus me-1"></i>Agregar a Cotización
                    </button>
                @endif
                
                @auth
                    @if(!auth()->user()->isAdmin())
                        <button type="button" 
                                class="btn btn-lg favorite-btn {{ auth()->user()->hasFavorited($product->id) ? 'btn-danger' : 'btn-outline-danger' }}" 
                                onclick="toggleFavorite('{{ $product->slug }}', this)"
                                data-product-slug="{{ $product->slug }}">
                            <i class="bi {{ auth()->user()->hasFavorited($product->id) ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                            <span>{{ auth()->user()->hasFavorited($product->id) ? 'En Favoritos' : 'Agregar a Favoritos' }}</span>
                        </button>
                    @endif
                @else
                    <a href="{{ route('login') }}?redirect={{ urlencode(request()->url()) }}" class="btn btn-outline-danger btn-lg" title="Inicia sesión para guardar favoritos">
                        <i class="bi bi-heart me-1"></i>Agregar a Favoritos
                    </a>
                @endauth
                <a href="#specs" class="btn btn-outline-secondary btn-lg">Ver especificaciones</a>
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
                                @if($rp->mainImage && $rp->mainImage->path)
                                    <img src="{{ image_url($rp->mainImage->path) }}" alt="{{ $rp->name }}" style="max-height:120px; object-fit:contain;" onerror="this.style.display='none';">
                                @elseif($rp->images->count() && $rp->images->first()->path)
                                    <img src="{{ image_url($rp->images->first()->path) }}" alt="{{ $rp->name }}" style="max-height:120px; object-fit:contain;" onerror="this.style.display='none';">
                                @else
                                    <i class="bi bi-image fs-1 text-muted"></i>
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
// Función para toggle de favoritos
function toggleFavorite(productSlug, button) {
    // Verificar si hay token CSRF disponible
    var csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token not found');
        return;
    }
    
    button.disabled = true;
    
    fetch(`/api/favorites/${productSlug}/toggle`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (response.status === 401) {
            // No autenticado, redirigir a login
            window.location.href = '{{ route("login") }}?redirect=' + encodeURIComponent(window.location.href);
            return null;
        }
        return response.json();
    })
    .then(data => {
        button.disabled = false;
        if (data && data.success) {
            const icon = button.querySelector('i');
            const text = button.querySelector('span');
            if (data.favorited) {
                button.classList.remove('btn-outline-danger');
                button.classList.add('btn-danger');
                icon.className = 'bi bi-heart-fill';
                if (text) text.textContent = 'En Favoritos';
            } else {
                button.classList.remove('btn-danger');
                button.classList.add('btn-outline-danger');
                icon.className = 'bi bi-heart';
                if (text) text.textContent = 'Agregar a Favoritos';
            }
        } else if (data && data.message) {
            alert(data.message);
        }
    })
    .catch(error => {
        button.disabled = false;
        console.error('Error:', error);
    });
}

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
                                        <img src="{{ image_url($img->path) }}" class="d-block w-100 img-fluid" style="object-fit:contain; max-height:90vh;" loading="lazy" alt="">
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
                <form id="requestForm" action="{{ route('product.request', $product) }}" method="POST" onsubmit="return submitRequestForm(event);">
                    @csrf
                    @guest
                    <div class="mb-3">
                        <label class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input class="form-control" name="name" required placeholder="Tu nombre completo">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input class="form-control" type="email" name="email" required placeholder="tu@email.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input class="form-control" name="phone" placeholder="(Opcional)">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Empresa</label>
                        <input class="form-control" name="company" placeholder="(Opcional)">
                    </div>
                    @else
                    <div class="alert alert-info mb-3">
                        <i class="bi bi-person-check me-2"></i>
                        Solicitando como: <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->email }})
                    </div>
                    @endguest
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Cantidad</label>
                            <input class="form-control" type="number" name="quantity" value="1" min="1" max="9999">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mensaje adicional</label>
                        <textarea class="form-control" name="message" rows="3" placeholder="Describe tus necesidades o preguntas sobre el producto..."></textarea>
                    </div>
                    <div class="d-flex justify-content-end pt-2">
                        <button class="btn btn-secondary me-2" type="button" data-bs-dismiss="modal">Cerrar</button>
                        <button class="btn btn-primary" type="submit" id="requestSubmitBtn"><i class="bi bi-send me-1"></i>Enviar solicitud</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Función global para enviar el formulario de solicitud
function submitRequestForm(e) {
    e.preventDefault();
    e.stopPropagation();
    
    var form = document.getElementById('requestForm');
    var btn = document.getElementById('requestSubmitBtn');
    var msg = document.getElementById('requestMessage');
    var originalBtnText = btn.innerHTML;
    
    // Limpiar mensaje previo
    msg.className = '';
    msg.innerHTML = '';
    
    // Deshabilitar botón y mostrar spinner
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Enviando...';
    
    var formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(function(response) {
        return response.json().then(function(data) {
            return { ok: response.ok, data: data };
        });
    })
    .then(function(result) {
        btn.disabled = false;
        btn.innerHTML = originalBtnText;
        
        if (result.ok && result.data.success) {
            msg.className = 'alert alert-success d-flex align-items-center';
            msg.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i><div><strong>¡Solicitud enviada!</strong><br><small>' + (result.data.message || 'Nos comunicaremos contigo pronto.') + '</small></div>';
            form.reset();
            form.style.display = 'none';
            
            // Cerrar modal después de 3 segundos
            setTimeout(function() {
                var modal = bootstrap.Modal.getInstance(document.getElementById('requestModal'));
                if (modal) modal.hide();
                setTimeout(function() {
                    form.style.display = 'block';
                    msg.className = '';
                    msg.innerHTML = '';
                }, 500);
            }, 3000);
        } else {
            msg.className = 'alert alert-danger d-flex align-items-center';
            msg.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-2"></i><div>' + (result.data.message || 'Ocurrió un error al enviar la solicitud.') + '</div>';
        }
    })
    .catch(function(error) {
        btn.disabled = false;
        btn.innerHTML = originalBtnText;
        msg.className = 'alert alert-danger d-flex align-items-center';
        msg.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-2"></i><div>Error de conexión. Por favor intenta de nuevo.</div>';
        console.error('Error:', error);
    });
    
    return false; // Prevenir envío tradicional del formulario
}
</script>
