@extends('layouts.app')

@section('title', 'Gestión de Cotizaciones')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">
                <i class="bi bi-receipt me-2 text-primary"></i>Cotizaciones Recibidas
            </h1>
            <p class="text-muted mb-0">Gestiona las cotizaciones enviadas por clientes</p>
        </div>
    </div>

    {{-- Estadísticas --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $stats['total'] }}</h3>
                    <small class="text-muted">Total</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md">
            <div class="card border-0 shadow-sm h-100 border-start border-primary border-3">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-primary">{{ $stats['sent'] }}</h3>
                    <small class="text-muted">Enviadas</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md">
            <div class="card border-0 shadow-sm h-100 border-start border-info border-3">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-info">{{ $stats['viewed'] }}</h3>
                    <small class="text-muted">Vistas</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md">
            <div class="card border-0 shadow-sm h-100 border-start border-success border-3">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-success">{{ $stats['accepted'] }}</h3>
                    <small class="text-muted">Aceptadas</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md">
            <div class="card border-0 shadow-sm h-100 border-start border-danger border-3">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-danger">{{ $stats['rejected'] }}</h3>
                    <small class="text-muted">Rechazadas</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Buscar por número, nombre o email..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Enviada</option>
                        <option value="viewed" {{ request('status') == 'viewed' ? 'selected' : '' }}>Vista</option>
                        <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Aceptada</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rechazada</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expirada</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>Filtrar
                    </button>
                    <a href="{{ route('admin.quotes.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nº Cotización</th>
                        <th>Cliente</th>
                        <th class="text-center">Items</th>
                        <th class="text-end">Total</th>
                        <th class="text-center">Estado</th>
                        <th>Fecha</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($quotes as $quote)
                        <tr>
                            <td>
                                <strong class="text-primary">{{ $quote->quote_number }}</strong>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $quote->customer_name }}</div>
                                <small class="text-muted">{{ $quote->customer_email }}</small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary">{{ $quote->items->count() }}</span>
                            </td>
                            <td class="text-end">
                                <strong>${{ number_format($quote->total, 2) }}</strong>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $quote->status_color }}">{{ $quote->status_label }}</span>
                            </td>
                            <td>
                                <div>{{ $quote->created_at->format('d/m/Y') }}</div>
                                <small class="text-muted">{{ $quote->created_at->format('H:i') }}</small>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.quotes.show', $quote) }}" class="btn btn-outline-primary" title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.quotes.pdf', $quote) }}" class="btn btn-outline-success" title="Descargar PDF">
                                        <i class="bi bi-file-pdf"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger" 
                                            onclick="if(confirm('¿Eliminar esta cotización?')) document.getElementById('delete-{{ $quote->id }}').submit();"
                                            title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <form id="delete-{{ $quote->id }}" action="{{ route('admin.quotes.destroy', $quote) }}" method="POST" class="d-none">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                No hay cotizaciones que mostrar
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($quotes->hasPages())
            <div class="card-footer bg-white">
                {{ $quotes->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
