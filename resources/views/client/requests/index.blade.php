@extends('layouts.app')

@section('title', 'Mis Solicitudes')

@section('styles')
<style>
    .requests-list {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    .request-row {
        display: flex;
        align-items: center;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        gap: 1rem;
        transition: background 0.2s;
        text-decoration: none;
        color: inherit;
    }

    .request-row:last-child {
        border-bottom: none;
    }

    .request-row:hover {
        background: #f8fafc;
    }

    .request-image {
        width: 80px;
        height: 80px;
        flex-shrink: 0;
        background: #f8fafc;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .request-image img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .request-image .no-image {
        font-size: 2rem;
        color: #cbd5e1;
    }

    .request-details {
        flex: 1;
        min-width: 0;
    }

    .request-name {
        font-size: 1rem;
        font-weight: 600;
        color: #0f2744;
        margin-bottom: 0.35rem;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .request-meta {
        font-size: 0.85rem;
        color: #64748b;
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .request-meta span {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .request-actions {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.5rem;
    }

    .filter-pills {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
    }

    .filter-pill {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
        border: 2px solid #e2e8f0;
        color: #64748b;
        background: white;
    }

    .filter-pill:hover {
        border-color: #3b82f6;
        color: #3b82f6;
    }

    .filter-pill.active {
        background: #3b82f6;
        border-color: #3b82f6;
        color: white;
    }

    @media (max-width: 768px) {
        .request-row {
            flex-wrap: wrap;
        }
        .request-actions {
            width: 100%;
            flex-direction: row;
            justify-content: space-between;
            margin-top: 0.75rem;
        }
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav class="client-breadcrumb">
        <a href="{{ route('client.dashboard') }}"><i class="bi bi-house"></i> Mi Cuenta</a>
        <span class="mx-2">/</span>
        <span class="text-dark">Mis Solicitudes</span>
    </nav>

    <!-- Header -->
    <div class="client-page-header">
        <h1 style="color:#fef2f2;"><i class="bi bi-envelope-paper me-2"  style="color:#fef2f2;"></i>Mis Solicitudes</h1>
        <p>{{ $requests->total() }} solicitudes realizadas</p>
    </div>

    <!-- Filtros -->
    <div class="filter-pills">
        <a href="{{ route('client.requests.index') }}" class="filter-pill {{ !request('status') ? 'active' : '' }}">
            Todas
        </a>
        <a href="{{ route('client.requests.index', ['status' => 'pending']) }}" class="filter-pill {{ request('status') == 'pending' ? 'active' : '' }}">
            <i class="bi bi-hourglass-split me-1"></i>Pendientes
        </a>
        <a href="{{ route('client.requests.index', ['status' => 'contacted']) }}" class="filter-pill {{ request('status') == 'contacted' ? 'active' : '' }}">
            <i class="bi bi-telephone me-1"></i>Contactadas
        </a>
        <a href="{{ route('client.requests.index', ['status' => 'completed']) }}" class="filter-pill {{ request('status') == 'completed' ? 'active' : '' }}">
            <i class="bi bi-check-circle me-1"></i>Completadas
        </a>
    </div>

    @if($requests->count() > 0)
        <div class="requests-list">
            @foreach($requests as $request)
                <a href="{{ route('client.requests.show', $request) }}" class="request-row">
                    <div class="request-image">
                        @if($request->product && $request->product->mainImage)
                            <img src="{{ image_url($request->product->mainImage->path) }}" alt="{{ $request->product->name }}">
                        @else
                            <i class="bi bi-image no-image"></i>
                        @endif
                    </div>
                    <div class="request-details">
                        <h3 class="request-name">{{ $request->product->name ?? 'Producto no disponible' }}</h3>
                        <div class="request-meta">
                            <span><i class="bi bi-calendar3"></i>{{ $request->created_at->format('d/m/Y') }}</span>
                            <span><i class="bi bi-hash"></i>SOL-{{ str_pad($request->id, 5, '0', STR_PAD_LEFT) }}</span>
                            @if($request->quantity > 1)
                                <span><i class="bi bi-box"></i>{{ $request->quantity }} unidades</span>
                            @endif
                        </div>
                    </div>
                    <div class="request-actions">
                        <span class="status-badge {{ $request->status }}">
                            {{ $request->status_label }}
                        </span>
                        <small class="text-muted"><i class="bi bi-arrow-right"></i> Ver detalles</small>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            {{ $requests->appends(request()->query())->links() }}
        </div>
    @else
        <div class="empty-state">
            <i class="bi bi-envelope-open"></i>
            <h3>No tienes solicitudes</h3>
            <p>Cuando solicites información sobre productos, aparecerán aquí para que puedas hacer seguimiento.</p>
            <a href="{{ route('catalog.index') }}" class="btn-explore">
                <i class="bi bi-shop"></i> Explorar catálogo
            </a>
        </div>
    @endif
</div>
@endsection
