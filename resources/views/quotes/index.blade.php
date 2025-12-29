@extends('layouts.app')
@section('title', 'Mi Cotización')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('catalog.index') }}">Catálogo</a></li>
                    <li class="breadcrumb-item active">Mi Cotización</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h4 class="mb-0"><i class="bi bi-cart3 me-2"></i>Mi Cotización</h4>
                </div>
                <div class="card-body p-0">
                    @if($quote && $quote->items->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:100px">Imagen</th>
                                        <th>Producto</th>
                                        <th style="width:120px">Precio</th>
                                        <th style="width:130px">Cantidad</th>
                                        <th style="width:120px">Subtotal</th>
                                        <th style="width:60px"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($quote->items as $item)
                                        <tr data-item-id="{{ $item->id }}">
                                            <td>
                                                @if($item->product->mainImage)
                                                    <img src="{{ image_url($item->product->mainImage->path) }}" 
                                                         alt="{{ $item->product->name }}" 
                                                         class="img-thumbnail" 
                                                         style="width:70px; height:70px; object-fit:contain;">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center" style="width:70px; height:70px;">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('catalog.show', $item->product) }}" class="text-decoration-none fw-medium">
                                                    {{ $item->product->name }}
                                                </a>
                                                <br>
                                                <small class="text-muted">SKU: {{ $item->product->sku_code }}</small>
                                            </td>
                                            <td class="text-nowrap">${{ number_format($item->unit_price, 2) }}</td>
                                            <td>
                                                <div class="input-group input-group-sm" style="width:100px">
                                                    <button class="btn btn-outline-secondary btn-qty" type="button" data-action="decrease" data-item="{{ $item->id }}">-</button>
                                                    <input type="number" class="form-control text-center qty-input" value="{{ $item->quantity }}" min="1" max="9999" data-item="{{ $item->id }}">
                                                    <button class="btn btn-outline-secondary btn-qty" type="button" data-action="increase" data-item="{{ $item->id }}">+</button>
                                                </div>
                                            </td>
                                            <td class="fw-bold item-subtotal">${{ number_format($item->subtotal, 2) }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-danger btn-remove" data-item="{{ $item->id }}" title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-cart-x display-1 text-muted"></i>
                            <h5 class="mt-3">Tu cotización está vacía</h5>
                            <p class="text-muted">Agrega productos del catálogo para solicitar una cotización</p>
                            <a href="{{ route('catalog.index') }}" class="btn btn-primary">
                                <i class="bi bi-shop me-1"></i>Ver Catálogo
                            </a>
                        </div>
                    @endif
                </div>
                @if($quote && $quote->items->count() > 0)
                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-outline-danger btn-clear">
                                <i class="bi bi-trash me-1"></i>Vaciar Cotización
                            </button>
                            <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-plus me-1"></i>Agregar más productos
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-4">
            @if($quote && $quote->items->count() > 0)
                <div class="card border-0 shadow-sm sticky-top" style="top:80px">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Resumen</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span id="quote-subtotal">${{ number_format($quote->subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>IVA ({{ get_tax_rate() }}%):</span>
                            <span id="quote-tax">${{ number_format($quote->tax, 2) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong class="text-primary fs-4" id="quote-total">${{ number_format($quote->total, 2) }}</strong>
                        </div>
                        <a href="{{ route('quote.checkout') }}" class="btn btn-primary w-100 btn-lg">
                            <i class="bi bi-send me-1"></i>Solicitar Cotización
                        </a>
                        <p class="text-muted small mt-2 mb-0 text-center">
                            <i class="bi bi-info-circle me-1"></i>
                            Te enviaremos la cotización formal por email
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Cambiar cantidad
    document.querySelectorAll('.btn-qty').forEach(btn => {
        btn.addEventListener('click', function() {
            const itemId = this.dataset.item;
            const input = document.querySelector(`.qty-input[data-item="${itemId}"]`);
            let qty = parseInt(input.value);
            
            if (this.dataset.action === 'increase') {
                qty = Math.min(qty + 1, 9999);
            } else {
                qty = Math.max(qty - 1, 1);
            }
            
            input.value = qty;
            updateQuantity(itemId, qty);
        });
    });
    
    // Input directo de cantidad
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('change', function() {
            const itemId = this.dataset.item;
            let qty = parseInt(this.value) || 1;
            qty = Math.max(1, Math.min(qty, 9999));
            this.value = qty;
            updateQuantity(itemId, qty);
        });
    });
    
    // Eliminar item
    document.querySelectorAll('.btn-remove').forEach(btn => {
        btn.addEventListener('click', function() {
            if (confirm('¿Eliminar este producto de la cotización?')) {
                removeItem(this.dataset.item);
            }
        });
    });
    
    // Vaciar cotización
    document.querySelector('.btn-clear')?.addEventListener('click', function() {
        if (confirm('¿Vaciar toda la cotización?')) {
            fetch('{{ route("quote.clear") }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            }).then(res => res.json()).then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    });
    
    function updateQuantity(itemId, qty) {
        fetch(`/cotizacion/item/${itemId}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ quantity: qty })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.querySelector(`tr[data-item-id="${itemId}"] .item-subtotal`).textContent = '$' + data.item_subtotal;
                document.getElementById('quote-subtotal').textContent = '$' + data.subtotal;
                document.getElementById('quote-tax').textContent = '$' + data.tax;
                document.getElementById('quote-total').textContent = '$' + data.total;
            }
        });
    }
    
    function removeItem(itemId) {
        fetch(`/cotizacion/item/${itemId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.querySelector(`tr[data-item-id="${itemId}"]`).remove();
                document.getElementById('quote-subtotal').textContent = '$' + data.subtotal;
                document.getElementById('quote-tax').textContent = '$' + data.tax;
                document.getElementById('quote-total').textContent = '$' + data.total;
                
                if (data.items_count === 0) {
                    location.reload();
                }
                
                updateCartBadge(data.items_count);
            }
        });
    }
    
    function updateCartBadge(count) {
        const badge = document.getElementById('quote-badge');
        if (badge) {
            badge.textContent = count;
            badge.style.display = count > 0 ? 'inline-block' : 'none';
        }
    }
});
</script>
@endpush
@endsection
