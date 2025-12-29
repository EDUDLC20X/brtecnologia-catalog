@extends('layouts.app')

@section('title', 'Cotización ' . $quote->quote_number)

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.quotes.index') }}" class="text-muted text-decoration-none mb-2 d-inline-block">
                <i class="bi bi-arrow-left me-1"></i>Volver a cotizaciones
            </a>
            <h1 class="h3 mb-0">
                <i class="bi bi-receipt me-2 text-primary"></i>Cotización {{ $quote->quote_number }}
            </h1>
        </div>
        <span class="badge bg-{{ $quote->status_color }} fs-6 px-3 py-2">{{ $quote->status_label }}</span>
    </div>

    <div class="row g-4">
        {{-- Columna principal --}}
        <div class="col-lg-8">
            {{-- Info del cliente --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-person me-2"></i>Datos del Cliente</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Nombre</label>
                            <p class="mb-0 fw-semibold">{{ $quote->customer_name }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Email</label>
                            <p class="mb-0">
                                <a href="mailto:{{ $quote->customer_email }}">{{ $quote->customer_email }}</a>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Teléfono</label>
                            <p class="mb-0">{{ $quote->customer_phone ?? 'No proporcionado' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Empresa</label>
                            <p class="mb-0">{{ $quote->customer_company ?? 'No proporcionada' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Productos --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-box me-2"></i>Productos Cotizados</h5>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-end">P. Unitario</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quote->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product && $item->product->mainImage)
                                                <img src="{{ image_url($item->product->mainImage->path) }}" 
                                                     alt="{{ $item->product->name }}"
                                                     class="me-3 rounded" 
                                                     style="width:50px; height:50px; object-fit:contain;">
                                            @endif
                                            <div>
                                                @if($item->product)
                                                    <a href="{{ route('catalog.show', $item->product->slug) }}" target="_blank" class="text-decoration-none">
                                                        {{ $item->product->name }}
                                                    </a>
                                                    <br><small class="text-muted">SKU: {{ $item->product->sku_code }}</small>
                                                @else
                                                    <span class="text-muted">Producto eliminado</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">${{ number_format($item->unit_price, 2) }}</td>
                                    <td class="text-end fw-bold">${{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end">Subtotal:</td>
                                <td class="text-end">${{ number_format($quote->subtotal, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end">IVA ({{ get_tax_rate() }}%):</td>
                                <td class="text-end">${{ number_format($quote->tax, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end"><strong>TOTAL:</strong></td>
                                <td class="text-end"><strong class="fs-5 text-primary">${{ number_format($quote->total, 2) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Notas --}}
            @if($quote->notes)
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-chat-text me-2"></i>Notas del Cliente</h5>
                    </div>
                    <div class="card-body">
                        <div class="bg-light rounded p-3">
                            {!! nl2br(e($quote->notes)) !!}
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Columna lateral --}}
        <div class="col-lg-4">
            {{-- Acciones --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="bi bi-lightning me-2"></i>Acciones</h6>
                </div>
                <div class="card-body d-grid gap-2">
                    <a href="{{ route('admin.quotes.pdf', $quote) }}" class="btn btn-success">
                        <i class="bi bi-file-pdf me-2"></i>Descargar PDF
                    </a>
                </div>
            </div>

            {{-- Cambiar Estado --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="bi bi-arrow-repeat me-2"></i>Cambiar Estado</h6>
                </div>
                <div class="card-body d-grid gap-2">
                    @php
                        $statuses = [
                            'sent' => ['label' => 'Enviada', 'color' => 'primary'],
                            'viewed' => ['label' => 'Vista', 'color' => 'info'],
                            'accepted' => ['label' => 'Aceptada', 'color' => 'success'],
                            'rejected' => ['label' => 'Rechazada', 'color' => 'danger'],
                            'expired' => ['label' => 'Expirada', 'color' => 'secondary'],
                        ];
                    @endphp
                    @foreach($statuses as $key => $st)
                        <form action="{{ route('admin.quotes.status', $quote) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="{{ $key }}">
                            <button type="submit" 
                                    class="btn btn-{{ $key == $quote->status ? '' : 'outline-' }}{{ $st['color'] }} w-100"
                                    {{ $key == $quote->status ? 'disabled' : '' }}>
                                {{ $st['label'] }}
                                @if($key == $quote->status)
                                    <i class="bi bi-check2 ms-1"></i>
                                @endif
                            </button>
                        </form>
                    @endforeach
                </div>
            </div>

            {{-- Info --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Información</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <small class="text-muted d-block">Número</small>
                            <strong>{{ $quote->quote_number }}</strong>
                        </li>
                        <li class="mb-2">
                            <small class="text-muted d-block">Fecha de Creación</small>
                            <strong>{{ $quote->created_at->format('d/m/Y H:i') }}</strong>
                        </li>
                        <li class="mb-2">
                            <small class="text-muted d-block">Válida Hasta</small>
                            <strong class="{{ $quote->isExpired() ? 'text-danger' : '' }}">
                                {{ $quote->valid_until?->format('d/m/Y') ?? 'Sin fecha' }}
                                @if($quote->isExpired())
                                    <span class="badge bg-danger ms-1">Expirada</span>
                                @endif
                            </strong>
                        </li>
                        <li class="mb-0">
                            <small class="text-muted d-block">Total Productos</small>
                            <strong>{{ $quote->items->sum('quantity') }} unidades</strong>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Zona peligrosa --}}
            <div class="card border-0 shadow-sm border-danger">
                <div class="card-header bg-white">
                    <h6 class="mb-0 text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Zona de Peligro</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.quotes.destroy', $quote) }}" method="POST"
                          onsubmit="return confirm('¿Eliminar esta cotización permanentemente?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bi bi-trash me-1"></i>Eliminar Cotización
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
