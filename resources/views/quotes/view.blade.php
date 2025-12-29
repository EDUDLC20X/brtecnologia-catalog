@extends('layouts.app')
@section('title', 'Cotización ' . $quote->quote_number)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">Cotización {{ $quote->quote_number }}</h4>
                        <small class="opacity-75">Generada el {{ $quote->created_at->format('d/m/Y H:i') }}</small>
                    </div>
                    <span class="badge bg-{{ $quote->status_color }} fs-6">{{ $quote->status_label }}</span>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Datos del Cliente</h6>
                            <p class="mb-1"><strong>{{ $quote->customer_name }}</strong></p>
                            <p class="mb-1 text-muted">{{ $quote->customer_email }}</p>
                            @if($quote->customer_phone)
                                <p class="mb-1 text-muted">Tel: {{ $quote->customer_phone }}</p>
                            @endif
                            @if($quote->customer_company)
                                <p class="mb-0 text-muted">Empresa: {{ $quote->customer_company }}</p>
                            @endif
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h6 class="text-muted mb-2">Información de Cotización</h6>
                            <p class="mb-1"><strong>Nº:</strong> {{ $quote->quote_number }}</p>
                            <p class="mb-1"><strong>Fecha:</strong> {{ $quote->created_at->format('d/m/Y') }}</p>
                            <p class="mb-0"><strong>Válida hasta:</strong> {{ $quote->valid_until?->format('d/m/Y') ?? 'N/A' }}</p>
                            @if($quote->isExpired())
                                <span class="badge bg-warning text-dark mt-2">Cotización Expirada</span>
                            @endif
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center" style="width:100px">Cantidad</th>
                                    <th class="text-end" style="width:120px">P. Unitario</th>
                                    <th class="text-end" style="width:120px">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($quote->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->product->mainImage)
                                                    <img src="{{ image_url($item->product->mainImage->path) }}" 
                                                         alt="{{ $item->product->name }}" 
                                                         class="me-3" 
                                                         style="width:50px; height:50px; object-fit:contain;">
                                                @endif
                                                <div>
                                                    <strong>{{ $item->product->name }}</strong><br>
                                                    <small class="text-muted">SKU: {{ $item->product->sku_code }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">${{ number_format($item->unit_price, 2) }}</td>
                                        <td class="text-end fw-bold">${{ number_format($item->subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end">Subtotal:</td>
                                    <td class="text-end">${{ number_format($quote->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end">IVA ({{ get_tax_rate() }}%):</td>
                                    <td class="text-end">${{ number_format($quote->tax, 2) }}</td>
                                </tr>
                                <tr class="table-primary">
                                    <td colspan="3" class="text-end"><strong>TOTAL:</strong></td>
                                    <td class="text-end"><strong class="fs-5">${{ number_format($quote->total, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if($quote->notes)
                        <div class="alert alert-warning mt-3">
                            <h6 class="alert-heading mb-2"><i class="bi bi-chat-text me-1"></i>Notas del cliente:</h6>
                            {{ $quote->notes }}
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-white d-flex justify-content-between">
                    <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-shop me-1"></i>Ver Catálogo
                    </a>
                    <a href="{{ route('quote.pdf', $quote) }}" class="btn btn-primary">
                        <i class="bi bi-file-pdf me-1"></i>Descargar PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
