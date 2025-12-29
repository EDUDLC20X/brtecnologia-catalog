@extends('layouts.app')
@section('title', 'Gestión de Categorías')

@section('styles')
<style>
    .admin-header {
        background: linear-gradient(135deg, #0f2744 0%, #1e3a5f 100%);
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
    .category-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
    }
    .category-card .card-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 1rem 1.25rem;
        font-weight: 600;
    }
    .table th {
        background: #f8fafc;
        font-weight: 600;
        color: #475569;
        border: none;
        padding: 0.75rem 1rem;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.05em;
    }
    .table td {
        padding: 0.75rem 1rem;
        vertical-align: middle;
    }
    .table tbody tr:hover {
        background: #f8fafc;
    }
    .badge-count {
        background: #dbeafe;
        color: #1e40af;
        font-weight: 600;
        padding: 0.35em 0.65em;
        border-radius: 6px;
    }
    .btn-action {
        padding: 0.375rem 0.75rem;
        font-size: 0.8rem;
        border-radius: 6px;
    }
    .empty-state {
        padding: 3rem;
        text-align: center;
        color: #64748b;
    }
    .empty-state i {
        font-size: 3rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="admin-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1 style="color:#fef2f2;"><i class="bi bi-tags me-2" style="color:#fef2f2;"></i>Gestión de Categorías</h1>
            <small class="opacity-75">Administra las categorías de productos</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-light btn-sm">
                <i class="bi bi-plus-circle me-1"></i> Nueva Categoría
            </a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>

    <!-- Tabla de Categorías -->
    <div class="category-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-list-ul me-2"></i>Listado de Categorías</span>
            <span class="badge bg-primary">{{ $categories->count() }} categorías</span>
        </div>
        <div class="card-body p-0">
            @if($categories->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Ícono</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th class="text-center">Productos</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                                <tr>
                                    <td><code>#{{ $category->id }}</code></td>
                                    <td>
                                        <div style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                                            <i class="bi {{ $category->icon ?? 'bi-box' }}" style="font-size: 1.25rem; color: #374151;"></i>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $category->name }}</strong>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ Str::limit($category->description, 50) ?: '—' }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge-count">{{ $category->products_count }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <a href="{{ route('catalog.index', ['categories' => [$category->id]]) }}" 
                                               class="btn btn-outline-secondary btn-action" title="Ver en catálogo">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.categories.edit', $category) }}" 
                                               class="btn btn-outline-primary btn-action" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.categories.destroy', $category) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('¿Estás seguro de eliminar esta categoría?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-action" title="Eliminar"
                                                        {{ $category->products_count > 0 ? 'disabled' : '' }}>
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="bi bi-tags d-block"></i>
                    <h5>No hay categorías</h5>
                    <p>Crea tu primera categoría para organizar los productos.</p>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Crear Categoría
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
