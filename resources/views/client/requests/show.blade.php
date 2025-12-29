@extends('layouts.app')

@section('title', 'Detalle de Solicitud')

@section('styles')
<style>
    /* Estilos específicos para request show */
    .product-preview {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 10px;
        margin-bottom: 1.5rem;
    }
    .product-preview img {
        width: 120px;
        height: 120px;
        object-fit: contain;
        background: white;
        border-radius: 8px;
        padding: 0.5rem;
    }
    .product-preview .no-image {
        width: 120px;
        height: 120px;
        background: white;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #cbd5e1;
        font-size: 2.5rem;
    }
    .product-preview-info h3 {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--primary-dark);
        margin-bottom: 0.5rem;
    }
    .product-preview-info .price {
        font-size: 1.25rem;
        font-weight: 700;
        color: #059669;
    }
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }
    .info-item {
        padding: 1rem;
        background: #f8fafc;
        border-radius: 8px;
    }
    .info-item label {
        font-size: 0.8rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
        display: block;
    }
    .info-item p {
        font-size: 1rem;
        font-weight: 600;
        color: var(--primary-dark);
        margin: 0;
    }
    .message-box {
        background: #f8fafc;
        border-radius: 8px;
        padding: 1.25rem;
        margin-top: 1.5rem;
    }
    .message-box h4 {
        font-size: 0.9rem;
        color: #64748b;
        margin-bottom: 0.75rem;
    }
    .message-box p {
        margin: 0;
        color: var(--primary-dark);
        line-height: 1.6;
    }
    .timeline {
        margin-top: 1.5rem;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 8px;
    }
    .timeline h4 {
        font-size: 0.9rem;
        color: #64748b;
        margin-bottom: 1rem;
    }
    .timeline-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 0.75rem 0;
        border-bottom: 1px dashed #e2e8f0;
    }
    .timeline-item:last-child { border-bottom: none; }
    .timeline-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
    }
    .timeline-icon.created { background: #dbeafe; color: #2563eb; }
    .timeline-icon.responded { background: #d1fae5; color: #059669; }
    .status-detail-card {
        text-align: center;
        padding: 1.5rem;
    }
    .status-detail-card i { font-size: 3rem; margin-bottom: 1rem; }
    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
        flex-wrap: wrap;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav class="client-breadcrumb">
        <a href="{{ route('client.dashboard') }}"><i class="bi bi-house"></i> Mi Cuenta</a>
        <span class="mx-2">/</span>
        <a href="{{ route('client.requests.index') }}">Mis Solicitudes</a>
        <span class="mx-2">/</span>
        <span class="text-dark">Detalle</span>
    </nav>

    <!-- Header -->
    <div class="client-page-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1><i class="bi bi-envelope-open me-2"></i>Solicitud #{{ $productRequest->id }}</h1>
                <p class="mb-0 opacity-75">Realizada el {{ $productRequest->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <span class="status-badge {{ $productRequest->status }}">
                @switch($productRequest->status)
                    @case('pending')
                        <i class="bi bi-hourglass-split"></i>
                        @break
                    @case('contacted')
                        <i class="bi bi-telephone-fill"></i>
                        @break
                    @case('quoted')
                        <i class="bi bi-file-text-fill"></i>
                        @break
                    @case('completed')
                        <i class="bi bi-check-circle-fill"></i>
                        @break
                    @case('cancelled')
                        <i class="bi bi-x-circle-fill"></i>
                        @break
                @endswitch
                {{ $productRequest->status_label }}
            </span>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="section-card">
                <div class="section-header">
                    <h2><i class="bi bi-box me-2"></i>Producto Solicitado</h2>
                </div>
                <div class="section-body">
                    @if($productRequest->product)
                        <div class="product-preview">
                            @if($productRequest->product->mainImage)
                                <img src="{{ image_url($productRequest->product->mainImage->path) }}" alt="{{ $productRequest->product->name }}">
                            @else
                                <div class="no-image"><i class="bi bi-image"></i></div>
                            @endif
                            <div class="product-preview-info">
                                <h3>{{ $productRequest->product->name }}</h3>
                                <p class="text-muted mb-2">{{ $productRequest->product->category->name ?? 'Sin categoría' }}</p>
                                <div class="price">${{ number_format($productRequest->product->price_base, 2) }}</div>
                                <a href="{{ route('catalog.show', $productRequest->product) }}" class="btn btn-sm btn-outline-primary mt-2">
                                    <i class="bi bi-eye"></i> Ver producto
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>El producto ya no está disponible.
                        </div>
                    @endif

                    <div class="info-grid">
                        <div class="info-item">
                            <label>Cantidad solicitada</label>
                            <p>{{ $productRequest->quantity }} unidad(es)</p>
                        </div>
                        <div class="info-item">
                            <label>Email de contacto</label>
                            <p>{{ $productRequest->email }}</p>
                        </div>
                        @if($productRequest->phone)
                            <div class="info-item">
                                <label>Teléfono</label>
                                <p>{{ $productRequest->phone }}</p>
                            </div>
                        @endif
                        @if($productRequest->company)
                            <div class="info-item">
                                <label>Empresa</label>
                                <p>{{ $productRequest->company }}</p>
                            </div>
                        @endif
                    </div>

                    @if($productRequest->message)
                        <div class="message-box">
                            <h4><i class="bi bi-chat-left-text me-2"></i>Tu mensaje</h4>
                            <p>{{ $productRequest->message }}</p>
                        </div>
                    @endif

                    <div class="timeline">
                        <h4><i class="bi bi-clock-history me-2"></i>Historial</h4>
                        <div class="timeline-item">
                            <div class="timeline-icon created">
                                <i class="bi bi-plus"></i>
                            </div>
                            <div>
                                <strong>Solicitud creada</strong>
                                <div class="text-muted small">{{ $productRequest->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                        @if($productRequest->responded_at)
                            <div class="timeline-item">
                                <div class="timeline-icon responded">
                                    <i class="bi bi-reply"></i>
                                </div>
                                <div>
                                    <strong>Respuesta recibida</strong>
                                    <div class="text-muted small">{{ $productRequest->responded_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="action-buttons">
                        <a href="{{ route('client.requests.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                        @if($productRequest->product)
                            <a href="{{ route('catalog.show', $productRequest->product) }}" class="btn btn-primary">
                                <i class="bi bi-eye"></i> Ver producto
                            </a>
                        @endif
                        @if($productRequest->status === 'pending')
                            <button type="button" class="btn btn-outline-danger" onclick="cancelRequest()">
                                <i class="bi bi-x-lg"></i> Cancelar
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="section-card">
                <div class="section-header">
                    <h2><i class="bi bi-info-circle me-2"></i>Estado</h2>
                </div>
                <div class="section-body status-detail-card">
                    @switch($productRequest->status)
                        @case('pending')
                            <i class="bi bi-hourglass-split text-warning"></i>
                            <h5 class="text-warning">Pendiente</h5>
                            <p class="text-muted small">Tu solicitud está siendo revisada. Te contactaremos pronto.</p>
                            @break
                        @case('contacted')
                            <i class="bi bi-telephone-fill text-info"></i>
                            <h5 class="text-info">Contactado</h5>
                            <p class="text-muted small">Nos hemos comunicado contigo. Revisa tu email o teléfono.</p>
                            @break
                        @case('quoted')
                            <i class="bi bi-file-text-fill text-primary"></i>
                            <h5 class="text-primary">Cotizado</h5>
                            <p class="text-muted small">Te hemos enviado una cotización. Revisa tu email.</p>
                            @break
                        @case('completed')
                            <i class="bi bi-check-circle-fill text-success"></i>
                            <h5 class="text-success">Completado</h5>
                            <p class="text-muted small">Esta solicitud ha sido procesada exitosamente.</p>
                            @break
                        @case('cancelled')
                            <i class="bi bi-x-circle-fill text-secondary"></i>
                            <h5 class="text-secondary">Cancelada</h5>
                            <p class="text-muted small">Esta solicitud fue cancelada.</p>
                            @break
                    @endswitch
                </div>
            </div>

            <div class="section-card mt-3">
                <div class="section-header">
                    <h2><i class="bi bi-question-circle me-2"></i>¿Necesitas ayuda?</h2>
                </div>
                <div class="section-body text-center">
                    <p class="small text-muted mb-3">Si tienes dudas sobre tu solicitud, contáctanos:</p>
                    <a href="{{ route('contact') }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="bi bi-envelope me-2"></i>Contactar soporte
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function cancelRequest() {
    if (!confirm('¿Estás seguro de cancelar esta solicitud?')) return;
    
    fetch('{{ route('client.requests.cancel', $productRequest) }}', {
        method: 'POST',
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
            alert(data.message || 'Error al cancelar la solicitud');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al procesar la solicitud');
    });
}
</script>
@endpush
@endsection
