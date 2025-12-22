@extends('layouts.app')
@section('title', 'Dashboard Admin')

@section('styles')
<style>
    :root {
        --admin-primary: #0f2744;
        --admin-secondary: #475569;
        --admin-accent: #3b82f6;
        --admin-light: #f8fafc;
        --admin-border: #e2e8f0;
    }
    
    .admin-header {
        background: linear-gradient(135deg, var(--admin-primary) 0%, #1e3a5f 100%);
        color: white;
        padding: 1.5rem 2rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
    }
    
    .admin-header h1 {
        font-weight: 600;
        font-size: 1.5rem;
        margin: 0;
    }
    
    .stat-card {
        background: white;
        border: 1px solid var(--admin-border);
        border-radius: 12px;
        padding: 1.25rem;
        transition: all 0.2s ease;
    }
    
    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }
    
    .stat-card .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    
    .stat-card .stat-icon.blue { background: #dbeafe; color: #2563eb; }
    .stat-card .stat-icon.green { background: #dcfce7; color: #16a34a; }
    .stat-card .stat-icon.amber { background: #fef3c7; color: #d97706; }
    .stat-card .stat-icon.red { background: #fee2e2; color: #dc2626; }
    
    .stat-card .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--admin-primary);
        line-height: 1.2;
    }
    
    .stat-card .stat-label {
        color: var(--admin-secondary);
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .stat-card .stat-sub {
        color: #94a3b8;
        font-size: 0.75rem;
    }
    
    .admin-card {
        background: white;
        border: 1px solid var(--admin-border);
        border-radius: 12px;
        overflow: hidden;
    }
    
    .admin-card .card-header {
        background: var(--admin-light);
        border-bottom: 1px solid var(--admin-border);
        padding: 1rem 1.25rem;
        font-weight: 600;
        color: var(--admin-primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .admin-card .card-header i {
        color: var(--admin-accent);
    }
    
    .admin-card .card-body {
        padding: 0;
    }
    
    .admin-table {
        margin: 0;
        font-size: 0.875rem;
    }
    
    .admin-table th {
        background: var(--admin-light);
        font-weight: 600;
        color: var(--admin-secondary);
        border: none;
        padding: 0.75rem 1rem;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.05em;
    }
    
    .admin-table td {
        padding: 0.75rem 1rem;
        border-color: var(--admin-border);
        vertical-align: middle;
    }
    
    .admin-table tbody tr:hover {
        background: var(--admin-light);
    }
    
    .badge-stock {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.35em 0.65em;
        border-radius: 6px;
    }
    
    .badge-stock.warning { background: #fef3c7; color: #92400e; }
    .badge-stock.danger { background: #fee2e2; color: #991b1b; }
    
    .btn-admin {
        font-size: 0.75rem;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-weight: 500;
    }
    
    .quick-link-card {
        background: white;
        border: 1px solid var(--admin-border);
        border-radius: 10px;
        padding: 1rem;
        text-align: center;
        transition: all 0.2s ease;
        text-decoration: none;
        color: var(--admin-primary);
        display: block;
    }
    
    .quick-link-card:hover {
        border-color: var(--admin-accent);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        color: var(--admin-accent);
    }
    
    .quick-link-card i {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        display: block;
        color: var(--admin-accent);
    }
    
    .quick-link-card span {
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .empty-state {
        padding: 2rem;
        text-align: center;
        color: var(--admin-secondary);
    }
    
    .empty-state i {
        font-size: 2rem;
        color: #cbd5e1;
        margin-bottom: 0.5rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="admin-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1><i class="bi bi-speedometer2 me-2"></i>Panel de Administración</h1>
            <small style="color: rgba(255,255,255,0.9); font-weight: 500;">Bienvenido, {{ auth()->user()->name }}</small>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.content.index') }}" class="btn btn-light btn-sm">
                <i class="bi bi-pencil-square me-1"></i> Contenido
            </a>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-light btn-sm">
                <i class="bi bi-tags me-1"></i> Categorías
            </a>
            <a href="{{ route('products.index') }}" class="btn btn-light btn-sm">
                <i class="bi bi-box-seam me-1"></i> Productos
            </a>
            <a href="{{ route('products.export') }}" class="btn btn-outline-light btn-sm">
                <i class="bi bi-download me-1"></i> Exportar
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="stat-card">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-value">{{ $totalProducts }}</div>
                        <div class="stat-label">Productos Totales</div>
                        <div class="stat-sub">{{ $activeProducts }} activos</div>
                    </div>
                    <div class="stat-icon blue">
                        <i class="bi bi-box-seam"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="stat-card">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-value">{{ \App\Models\SiteContent::count() }}</div>
                        <div class="stat-label">Contenido Editable</div>
                        <div class="stat-sub">elementos del CMS</div>
                    </div>
                    <div class="stat-icon green">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="stat-card">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-value">{{ $totalUsers }}</div>
                        <div class="stat-label">Usuarios</div>
                        <div class="stat-sub">registrados</div>
                    </div>
                    <div class="stat-icon amber">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="stat-card">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-value">{{ $lowStockProducts }}</div>
                        <div class="stat-label">Stock Bajo</div>
                        <div class="stat-sub">{{ $outOfStockProducts }} agotados</div>
                    </div>
                    <div class="stat-icon red">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="row g-4 mb-4">
        <!-- Stock Bajo -->
        <div class="col-lg-6">
            <div class="admin-card">
                <div class="card-header">
                    <i class="bi bi-exclamation-circle"></i>
                    Productos con Stock Bajo
                </div>
                <div class="card-body">
                    @if($lowStockItems->count() > 0)
                        <div class="table-responsive">
                            <table class="table admin-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Categoría</th>
                                        <th>Stock</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lowStockItems as $item)
                                        <tr>
                                            <td><span class="fw-medium">{{ Str::limit($item->name, 25) }}</span></td>
                                            <td><span class="text-muted">{{ $item->category->name ?? '-' }}</span></td>
                                            <td><span class="badge badge-stock warning">{{ $item->stock_available }}</span></td>
                                            <td>
                                                <a href="{{ route('products.edit', $item) }}" class="btn btn-outline-primary btn-admin">
                                                    Editar
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="bi bi-check-circle"></i>
                            <p class="mb-0">Todo el stock está bien</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sin Stock -->
        <div class="col-lg-6">
            <div class="admin-card">
                <div class="card-header">
                    <i class="bi bi-x-circle"></i>
                    Productos Sin Stock
                </div>
                <div class="card-body">
                    @if($outOfStock->count() > 0)
                        <div class="table-responsive">
                            <table class="table admin-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>SKU</th>
                                        <th>Categoría</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($outOfStock as $item)
                                        <tr>
                                            <td><span class="fw-medium">{{ Str::limit($item->name, 20) }}</span></td>
                                            <td><code class="text-muted">{{ $item->sku_code }}</code></td>
                                            <td><span class="text-muted">{{ $item->category->name ?? '-' }}</span></td>
                                            <td>
                                                <a href="{{ route('products.edit', $item) }}" class="btn btn-outline-danger btn-admin">
                                                    Editar
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="bi bi-check-circle"></i>
                            <p class="mb-0">Sin productos agotados</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links Section -->
    <div class="admin-card mb-4">
        <div class="card-header">
            <i class="bi bi-grid"></i>
            Gestión de Contenido del Sitio
        </div>
        <div class="card-body p-4">
            <p class="text-muted mb-3" style="font-size: 0.875rem;">
                Edita el contenido de las páginas públicas sin necesidad de modificar código.
            </p>
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <a href="{{ route('admin.content.section', 'global') }}" class="quick-link-card">
                        <i class="bi bi-gear"></i>
                        <span>Global</span>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ route('admin.content.section', 'home') }}" class="quick-link-card">
                        <i class="bi bi-house"></i>
                        <span>Inicio</span>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ route('admin.content.section', 'about') }}" class="quick-link-card">
                        <i class="bi bi-info-circle"></i>
                        <span>Acerca de</span>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ route('admin.content.section', 'contact') }}" class="quick-link-card">
                        <i class="bi bi-envelope"></i>
                        <span>Contacto</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
