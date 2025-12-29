@extends('layouts.app')

@section('title', 'Recomendados para ti')

@section('styles')
<style>
    .recommendation-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: #fef3c7;
        color: #92400e;
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.25rem 0.6rem;
        border-radius: 20px;
        display: flex;
        align-items: center;
        gap: 0.25rem;
        z-index: 1;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav class="client-breadcrumb">
        <a href="{{ route('client.dashboard') }}"><i class="bi bi-house"></i> Mi Cuenta</a>
        <span class="mx-2">/</span>
        <span class="text-dark">Recomendaciones</span>
    </nav>

    <!-- Header -->
    <div class="client-page-header mb-4">
        <h1><i class="bi bi-stars me-2"></i>Recomendados para ti</h1>
        <p class="mb-0 opacity-75">Productos seleccionados basados en tu historial de navegación</p>
    </div>

    @if($recommendations->count() > 0)
        <div class="products-grid">
            @foreach($recommendations as $product)
                <div class="product-card-client">
                    <div class="product-image">
                        <span class="recommendation-badge">
                            <i class="bi bi-stars"></i> Para ti
                        </span>
                        @if($product->mainImage)
                            <img src="{{ image_url($product->mainImage->path) }}" alt="{{ $product->name }}" onerror="this.parentElement.innerHTML='<i class=\'bi bi-image no-image\'></i>';">
                        @else
                            <i class="bi bi-image no-image"></i>
                        @endif
                        <div class="product-actions">
                            <button type="button" class="favorite-btn" onclick="toggleFavorite({{ $product->id }}, this)" data-product-id="{{ $product->id }}">
                                <i class="bi bi-heart"></i>
                            </button>
                        </div>
                    </div>
                    <div class="product-body">
                        <div class="product-category">{{ $product->category->name ?? 'Sin categoría' }}</div>
                        <h3 class="product-name">
                            <a href="{{ route('catalog.show', $product) }}">{{ $product->name }}</a>
                        </h3>
                        <div class="product-price">${{ number_format($product->price_base, 2) }}</div>
                    </div>
                    <div class="product-footer">
                        <a href="{{ route('catalog.show', $product) }}" class="btn-view">
                            <i class="bi bi-eye me-1"></i> Ver producto
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="bi bi-stars"></i>
            <h3>Aún no tenemos recomendaciones</h3>
            <p>Explora el catálogo para que podamos conocer tus intereses y ofrecerte productos relevantes.</p>
            <a href="{{ route('catalog.index') }}" class="btn btn-primary">
                <i class="bi bi-shop me-2"></i>Explorar catálogo
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script>
function toggleFavorite(productId, button) {
    fetch(`/api/favorites/${productId}/toggle`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const icon = button.querySelector('i');
            if (data.favorited) {
                button.classList.add('active');
                icon.className = 'bi bi-heart-fill';
            } else {
                button.classList.remove('active');
                icon.className = 'bi bi-heart';
            }
        }
    })
    .catch(error => console.error('Error:', error));
}

// Check initial favorite status
document.addEventListener('DOMContentLoaded', function() {
    const productIds = Array.from(document.querySelectorAll('.favorite-btn')).map(btn => parseInt(btn.dataset.productId));
    
    if (productIds.length > 0) {
        fetch('/api/favorites/check-multiple', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ product_ids: productIds })
        })
        .then(response => response.json())
        .then(data => {
            data.favorited_ids.forEach(id => {
                const btn = document.querySelector(`.favorite-btn[data-product-id="${id}"]`);
                if (btn) {
                    btn.classList.add('active');
                    btn.querySelector('i').className = 'bi bi-heart-fill';
                }
            });
        });
    }
});
</script>
@endpush
@endsection
