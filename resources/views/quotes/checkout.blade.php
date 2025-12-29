@extends('layouts.app')
@section('title', 'Finalizar Cotización')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('quote.index') }}">Mi Cotización</a></li>
                    <li class="breadcrumb-item active">Finalizar</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0"><i class="bi bi-person-lines-fill me-2"></i>Tus Datos</h4>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('quote.send') }}" method="POST" id="checkoutForm">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre completo <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="customer_name" 
                                       value="{{ old('customer_name', $quote->customer_name ?? auth()->user()?->name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Correo electrónico <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="customer_email" 
                                       value="{{ old('customer_email', $quote->customer_email ?? auth()->user()?->email) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" name="customer_phone" 
                                       value="{{ old('customer_phone', $quote->customer_phone ?? auth()->user()?->phone) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Empresa</label>
                                <input type="text" class="form-control" name="customer_company" 
                                       value="{{ old('customer_company', $quote->customer_company) }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Notas o comentarios</label>
                                <textarea class="form-control" name="notes" rows="3" 
                                          placeholder="Indícanos cualquier detalle adicional sobre tu cotización...">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('quote.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-1"></i>Volver
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-send me-1"></i>Enviar Cotización
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm sticky-top" style="top:80px">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>Resumen de Productos</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach($quote->items as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="me-2">
                                    <div class="fw-medium">{{ Str::limit($item->product->name, 40) }}</div>
                                    <small class="text-muted">{{ $item->quantity }} x ${{ number_format($item->unit_price, 2) }}</small>
                                </div>
                                <span class="text-nowrap">${{ number_format($item->subtotal, 2) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Subtotal:</span>
                        <span>${{ number_format($quote->subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>IVA ({{ get_tax_rate() }}%):</span>
                        <span>${{ number_format($quote->tax, 2) }}</span>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between">
                        <strong>Total:</strong>
                        <strong class="text-primary fs-5">${{ number_format($quote->total, 2) }}</strong>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-3">
                <div class="card-body">
                    <h6 class="mb-3"><i class="bi bi-shield-check text-success me-2"></i>¿Qué sigue?</h6>
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-2"><i class="bi bi-1-circle text-primary me-2"></i>Recibirás la cotización en tu correo</li>
                        <li class="mb-2"><i class="bi bi-2-circle text-primary me-2"></i>Nuestro equipo revisará tu solicitud</li>
                        <li class="mb-2"><i class="bi bi-3-circle text-primary me-2"></i>Te contactaremos para confirmar detalles</li>
                        <li><i class="bi bi-4-circle text-primary me-2"></i>Cotización válida por 15 días</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
