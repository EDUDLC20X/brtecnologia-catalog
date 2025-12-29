@extends('layouts.app')

@section('title', 'Mi Historial')

@section('styles')
<style>
    .btn-clear-history {
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
        padding: 0.6rem 1.25rem;
        border-radius: 8px;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-clear-history:hover {
        background: rgba(255,255,255,0.25);
        color: white;
    }

    .view-count {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(0,0,0,0.7);
        color: white;
        font-size: 0.75rem;
        padding: 0.25rem 0.6rem;
        border-radius: 20px;
    }

    .view-date {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: rgba(255,255,255,0.95);
        color: #64748b;
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav class="client-breadcrumb">
        <a href="{{ route('client.dashboard') }}"><i class="bi bi-house"></i> Mi Cuenta</a>
        <span class="mx-2">/</span>
        <span class="text-dark">Historial</span>
    </nav>

    <!-- Header -->
    <div class="client-page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 w-100">
            <div>
                <h1><i class="bi bi-clock-history me-2"></i>Mi Historial</h1>
                <p>{{ $history->total() }} productos vistos</p>
            </div>
            @if($history->count() > 0)
                <button type="button" class="btn-clear-history" onclick="clearHistory()">
                    <i class="bi bi-trash"></i> Limpiar historial
                </button>
            @endif
        </div>
    </div>

    @if($history->count() > 0)
        <div class="products-grid">
            @foreach($history as $view)
                <div class="product-card-client" id="history-{{ $view->product_id }}">
                    <div class="product-image">
                        <span class="view-count"><i class="bi bi-eye me-1"></i>{{ $view->view_count }} {{ $view->view_count == 1 ? 'vez' : 'veces' }}</span>
                        @if($view->product && $view->product->mainImage)
                            <img src="{{ image_url($view->product->mainImage->path) }}" alt="{{ $view->product->name }}" onerror="this.parentElement.innerHTML='<i class=\'bi bi-image no-image\'></i>';">
                        @else
                            <i class="bi bi-image no-image"></i>
                        @endif
                        <span class="view-date">{{ $view->last_viewed_at->diffForHumans() }}</span>
                        <div class="product-actions">
                            <button type="button" class="action-btn" onclick="toggleFavorite({{ $view->product_id }}, this)" title="Agregar a favoritos">
                                <i class="bi bi-heart"></i>
                            </button>
                            <button type="button" class="action-btn remove" onclick="removeFromHistory({{ $view->product_id }})" title="Quitar del historial">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>
                    <div class="product-body">
                        @if($view->product)
                            <div class="product-category">{{ $view->product->category->name ?? 'Sin categoría' }}</div>
                            <h3 class="product-name">
                                <a href="{{ route('catalog.show', $view->product) }}">{{ $view->product->name }}</a>
                            </h3>
                            <div class="product-price">${{ number_format($view->product->price_base, 2) }}</div>
                        @else
                            <div class="product-name text-muted">Producto no disponible</div>
                        @endif
                    </div>
                    @if($view->product)
                        <div class="product-footer">
                            <a href="{{ route('catalog.show', $view->product) }}" class="btn-view">
                                <i class="bi bi-eye"></i> Ver detalles
                            </a>
                            <a href="{{ route('catalog.show', $view->product) }}#requestModal" class="btn-request">
                                <i class="bi bi-envelope"></i> Solicitar
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            {{ $history->links() }}
        </div>
    @else
        <div class="empty-state">
            <i class="bi bi-clock-history"></i>
            <h3>No hay historial</h3>
            <p>Los productos que visites aparecerán aquí para que puedas encontrarlos fácilmente.</p>
            <a href="{{ route('catalog.index') }}" class="btn-explore">
                <i class="bi bi-shop"></i> Explorar catálogo
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script>
function clearHistory() {
    if (!confirm('¿Limpiar todo el historial de navegación?')) return;
    
    fetch('{{ route("client.history.clear") }}', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.message || 'Error al limpiar historial');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error de conexión');
    });
}

function removeFromHistory(productId) {
    fetch(`{{ url('/mi-cuenta/historial') }}/${productId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const card = document.getElementById(`history-${productId}`);
            if (card) {
                card.style.opacity = '0';
                card.style.transform = 'scale(0.8)';
                card.style.transition = 'all 0.3s ease';
                setTimeout(() => card.remove(), 300);
            }
        }
    })
    .catch(error => console.error('Error:', error));
}

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
                icon.className = 'bi bi-heart-fill';
                button.style.color = '#dc2626';
            } else {
                icon.className = 'bi bi-heart';
                button.style.color = '';
            }
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
@endpush
@endsection
