@extends('layouts.app')
@section('title', 'Editar Categoría')

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
    .form-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
    }
    .form-card .card-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 1rem 1.25rem;
        font-weight: 600;
    }
    .form-card .card-body {
        padding: 1.5rem;
    }
    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .required-mark {
        color: #dc2626;
    }
    .info-badge {
        background: #dbeafe;
        color: #1e40af;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="admin-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1><i class="bi bi-pencil-square me-2"></i>Editar Categoría</h1>
            <small class="opacity-75">Modificar: {{ $category->name }}</small>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-light btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    <!-- Errores de validación -->
    @if($errors->any())
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Por favor corrige los siguientes errores:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulario -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="form-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-tag me-2"></i>Información de la Categoría</span>
                    <span class="info-badge">
                        <i class="bi bi-box-seam me-1"></i>{{ $category->products()->count() }} productos
                    </span>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="name" class="form-label">
                                Nombre <span class="required-mark">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $category->name) }}" 
                                   placeholder="Ej: Herramientas Eléctricas"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">El nombre debe ser único y descriptivo.</small>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3"
                                      placeholder="Descripción opcional de la categoría...">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <div>
                                @if($category->products()->count() == 0)
                                    <button type="button" class="btn btn-outline-danger" 
                                            onclick="if(confirm('¿Estás seguro de eliminar esta categoría?')) document.getElementById('delete-form').submit();">
                                        <i class="bi bi-trash me-1"></i> Eliminar
                                    </button>
                                @endif
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-1"></i> Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Formulario oculto para eliminar -->
                    <form id="delete-form" action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
