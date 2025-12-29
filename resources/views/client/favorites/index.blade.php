@extends('layouts.app')

@section('title', 'Mis Favoritos')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav class="client-breadcrumb">
        <a href="{{ route('client.dashboard') }}"><i class="bi bi-house"></i> Mi Cuenta</a>
        <span class="mx-2">/</span>
        <span class="text-dark">Mis Favoritos</span>
    </nav>

    <!-- Header -->
    <div class="client-page-header">
        <h1><i class="bi bi-heart-fill me-2"></i>Mis Favoritos</h1>
        <p>{{ $favorites->total() }} productos guardados</p>
    </div>

    @if($favorites->count() > 0)
        <div class="products-grid">
            @foreach($favorites as $favorite)
                <div class="product-card-client" id="favorite-{{ $favorite->product_id }}">
                    <div class="product-image">
                        @if($favorite->product && $favorite->product->mainImage)
                            <img src="{{ image_url($favorite->product->mainImage->path) }}" alt="{{ $favorite->product->name }}" onerror="this.parentElement.innerHTML='<i class=\'bi bi-image no-image\'></i>';">
                        @else
                            <i class="bi bi-image no-image"></i>
                        @endif
                        <div class="product-actions">
                            <button type="button" class="action-btn remove" onclick="removeFavorite({{ $favorite->product_id }})" title="Quitar de favoritos">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="product-body">
                        @if($favorite->product)
                            <div class="product-category">{{ $favorite->product->category->name ?? 'Sin categoría' }}</div>
                            <h3 class="product-name">
                                <a href="{{ route('catalog.show', $favorite->product) }}">{{ $favorite->product->name }}</a>
                            </h3>
                            <div class="product-price">${{ number_format($favorite->product->price_base, 2) }}</div>
                        @else
                            <div class="product-name text-muted">Producto no disponible</div>
                        @endif
                        <div class="product-date">
                            <i class="bi bi-calendar3 me-1"></i>Guardado el {{ $favorite->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                    @if($favorite->product)
                        <div class="product-footer">
                            <a href="{{ route('catalog.show', $favorite->product) }}" class="btn-view">
                                <i class="bi bi-eye"></i> Ver detalles
                            </a>
                            <a href="{{ route('catalog.show', $favorite->product) }}#requestModal" class="btn-request">
                                <i class="bi bi-envelope"></i> Solicitar
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            {{ $favorites->links() }}
        </div>
    @else
        <div class="empty-state">
            <i class="bi bi-heart"></i>
            <h3>No tienes favoritos aún</h3>
            <p>Explora el catálogo y guarda los productos que te interesen para encontrarlos fácilmente.</p>
            <a href="{{ route('catalog.index') }}" class="btn-explore">
                <i class="bi bi-shop"></i> Explorar catálogo
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script>
function removeFavorite(productId) {
    if (!confirm('¿Quitar este producto de favoritos?')) return;
    
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
            const card = document.getElementById(`favorite-${productId}`);
            if (card) {
                card.style.opacity = '0';
                card.style.transform = 'scale(0.8)';
                card.style.transition = 'all 0.3s ease';
                setTimeout(() => card.remove(), 300);
            }
        } else {
            alert(data.message || 'Error al quitar de favoritos');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error de conexión');
    });
}
</script>
@endpush
@endsection
