@extends('layouts.app')

@section('title', 'Gestión de Solicitudes')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">
                <i class="bi bi-envelope-paper me-2 text-primary"></i>Gestión de Solicitudes
            </h1>
            <p class="text-muted mb-0">Administra las solicitudes de información de productos</p>
        </div>
        <a href="{{ route('admin.requests.export', request()->query()) }}" class="btn btn-outline-success">
            <i class="bi bi-file-earmark-excel me-1"></i>Exportar CSV
        </a>
    </div>

    {{-- Tarjetas de estadísticas --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-lg">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-dark">{{ $statusCounts['total'] }}</h3>
                    <small class="text-muted">Total</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg">
            <div class="card shadow-sm h-100 border-start border-warning border-4">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-warning">{{ $statusCounts['pending'] }}</h3>
                    <small class="text-muted">Pendientes</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg">
            <div class="card shadow-sm h-100 border-start border-info border-4">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-info">{{ $statusCounts['contacted'] }}</h3>
                    <small class="text-muted">Contactados</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg">
            <div class="card shadow-sm h-100 border-start border-success border-4">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-success">{{ $statusCounts['completed'] }}</h3>
                    <small class="text-muted">Completadas</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg">
            <div class="card shadow-sm h-100 border-start border-secondary border-4">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-secondary">{{ $statusCounts['cancelled'] }}</h3>
                    <small class="text-muted">Canceladas</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.requests.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small text-muted">Buscar</label>
                    <input type="text" name="search" class="form-control" placeholder="Nombre, email o producto..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">Estado</label>
                    <select name="status" class="form-select">
                        <option value="">Todos</option>
                        @foreach($statuses as $key => $status)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                {{ $status['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">Desde</label>
                    <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">Hasta</label>
                    <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>Filtrar
                    </button>
                    <a href="{{ route('admin.requests.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla de solicitudes --}}
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:80px">ID</th>
                        <th>Cliente</th>
                        <th>Producto</th>
                        <th class="text-center">Estado</th>
                        <th>Fecha</th>
                        <th class="text-end" style="width:200px">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $request)
                        <tr>
                            <td>
                                <span class="badge bg-light text-dark">#{{ $request->id }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $request->name }}</div>
                                <small class="text-muted">{{ $request->email }}</small>
                                @if($request->phone)
                                    <br><small class="text-muted"><i class="bi bi-telephone me-1"></i>{{ $request->phone }}</small>
                                @endif
                            </td>
                            <td>
                                @if($request->product)
                                    <a href="{{ route('catalog.show', $request->product->slug) }}" target="_blank" class="text-decoration-none">
                                        {{ Str::limit($request->product->name, 40) }}
                                    </a>
                                @else
                                    <span class="text-muted">Producto eliminado</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @php
                                    $status = $statuses[$request->status] ?? ['label' => $request->status, 'color' => 'secondary', 'icon' => 'question'];
                                @endphp
                                <span class="badge bg-{{ $status['color'] }}">
                                    <i class="bi bi-{{ $status['icon'] }} me-1"></i>{{ $status['label'] }}
                                </span>
                            </td>
                            <td>
                                <div>{{ $request->created_at->format('d/m/Y') }}</div>
                                <small class="text-muted">{{ $request->created_at->format('H:i') }}</small>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    {{-- Cambio rápido de estado --}}
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" title="Cambiar estado">
                                            <i class="bi bi-arrow-repeat"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @foreach($statuses as $key => $st)
                                                @if($key != $request->status)
                                                    <li>
                                                        <form action="{{ route('admin.requests.status', $request) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="{{ $key }}">
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="bi bi-{{ $st['icon'] }} text-{{ $st['color'] }} me-2"></i>{{ $st['label'] }}
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                    
                                    <a href="{{ route('admin.requests.show', $request) }}" class="btn btn-outline-primary" title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    
                                    <button type="button" class="btn btn-outline-danger" 
                                            onclick="if(confirm('¿Eliminar esta solicitud?')) document.getElementById('delete-{{ $request->id }}').submit();"
                                            title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <form id="delete-{{ $request->id }}" action="{{ route('admin.requests.destroy', $request) }}" method="POST" class="d-none">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                No hay solicitudes que coincidan con los filtros
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($requests->hasPages())
            <div class="card-footer bg-white">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
