@extends('layouts.app')

@section('title', 'Productos')

@section('content')

<div class="container-fluid py-4">

    {{-- Aviso si Cloudinary no está configurado --}}
    @if(!config('services.cloudinary.cloud_name') || !config('services.cloudinary.api_key'))
        <div class="alert alert-warning alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Almacenamiento de imágenes no configurado.</strong> 
            Las imágenes subidas se perderán cuando el servidor se reinicie. 
            <a href="https://cloudinary.com/users/register_free" target="_blank" class="alert-link">Configura Cloudinary (gratis)</a> 
            y agrega las variables <code>CLOUDINARY_CLOUD_NAME</code>, <code>CLOUDINARY_API_KEY</code>, <code>CLOUDINARY_API_SECRET</code> en Render.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Header: title + primary action -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center gap-3">
            <h1 class="h4 mb-0"><i class="bi bi-box-seam me-2"></i>Catálogo de Productos</h1>
            <small class="text-muted">Administración</small>
        </div>

        <div>
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Nuevo Producto
            </a>
        </div>
    </div>

    <!-- Toolbar: search (md+), and offcanvas filters -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center gap-2">
            <form action="{{ route('products.index') }}" method="GET" class="d-none d-md-flex align-items-center">
                <div class="input-group input-group-sm" style="width:420px;">
                    <input type="search" name="search" class="form-control" placeholder="Buscar por nombre, SKU o descripción" value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
                </div>
            </form>

            <button class="btn btn-outline-secondary d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#searchOffcanvas" aria-controls="searchOffcanvas"><i class="bi bi-search"></i></button>
            <button class="btn btn-outline-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#filtersOffcanvas" aria-controls="filtersOffcanvas"><i class="bi bi-funnel me-1"></i> Filtros</button>
        </div>

        <div class="text-muted small">Mostrando {{ $products->total() }} productos</div>
    </div>

    <!-- Offcanvas: Filters (reused component) -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="filtersOffcanvas" aria-labelledby="filtersOffcanvasLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="filtersOffcanvasLabel">Filtros</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <x-filters-sidebar :categories="$categories" :priceRange="$priceRange" :brands="$brands" :action="route('products.index')" />
        </div>
    </div>

    <div class="offcanvas offcanvas-top" tabindex="-1" id="searchOffcanvas" aria-labelledby="searchOffcanvasLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="searchOffcanvasLabel">Buscar productos</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form action="{{ route('products.index') }}" method="GET" class="d-flex gap-2">
                <input type="search" name="search" class="form-control" placeholder="Buscar producto..." value="{{ request('search') }}" aria-label="Buscar">
                <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
            </form>
        </div>
    </div>

    <!-- Table: products -->
    @if($products->count() > 0)
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Imagen</th>
                                <th scope="col">#</th>
                                <th scope="col">SKU</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Categoría</th>
                                <th scope="col">Precio</th>
                                <th scope="col">Stock</th>
                                <th scope="col">Estado</th>
                                <th scope="col" class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td style="width:72px;">
                                        @if($product->mainImage && $product->mainImage->path)
                                            <img src="{{ image_url($product->mainImage->path) }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="rounded" 
                                                 style="width:56px;height:56px;object-fit:cover;"
                                                 onerror="this.onerror=null; this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2256%22 height=%2256%22 viewBox=%220 0 56 56%22><rect fill=%22%23e9ecef%22 width=%2256%22 height=%2256%22/><text x=%2228%22 y=%2232%22 text-anchor=%22middle%22 fill=%22%236c757d%22 font-size=%2212%22>📷</text></svg>';">
                                        @elseif($product->images && $product->images->count())
                                            <img src="{{ image_url($product->images->first()->path) }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="rounded" 
                                                 style="width:56px;height:56px;object-fit:cover;"
                                                 onerror="this.onerror=null; this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2256%22 height=%2256%22 viewBox=%220 0 56 56%22><rect fill=%22%23e9ecef%22 width=%2256%22 height=%2256%22/><text x=%2228%22 y=%2232%22 text-anchor=%22middle%22 fill=%22%236c757d%22 font-size=%2212%22>📷</text></svg>';">
                                        @else
                                            <div class="bg-light text-center rounded" style="width:56px;height:56px;display:flex;align-items:center;justify-content:center;color:#6c757d;">📷</div>
                                        @endif
                                    </td>
                                    <td style="width:56px;">{{ $product->id }}</td>
                                    <td><code>{{ $product->sku_code }}</code></td>
                                    <td>{{ $product->name }}</td>
                                    <td>
                                        @if($product->category)
                                            <span class="badge bg-info">{{ $product->category->name }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>${{ number_format($product->price_base, 2) }}</td>
                                    <td>
                                        @if($product->stock_available > 0)
                                            <span class="badge bg-success">{{ $product->stock_available }}</span>
                                        @else
                                            <span class="badge bg-danger">Agotado</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($product->is_active)
                                            <span class="badge bg-success">Activo</span>
                                        @else
                                            <span class="badge bg-secondary">Inactivo</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-warning me-1" title="Editar"><i class="bi bi-pencil"></i></a>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este producto?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $products->links() }}
        </div>

    @else
        <div class="alert alert-secondary text-center py-5">
            <p>📭 No hay productos registrados.</p>
            <a href="{{ route('products.create') }}" class="btn btn-primary">Crear el primero</a>
        </div>
    @endif

</div>

@endsection

