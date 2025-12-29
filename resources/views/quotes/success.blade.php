@extends('layouts.app')
@section('title', 'Cotización Enviada')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body py-5">
                    <div class="mb-4">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width:100px; height:100px;">
                            <i class="bi bi-check-circle-fill text-success" style="font-size:3rem;"></i>
                        </div>
                    </div>
                    
                    <h2 class="mb-3">¡Cotización Enviada!</h2>
                    <p class="text-muted mb-4">
                        Tu cotización <strong class="text-primary">{{ $quote->quote_number }}</strong> ha sido enviada correctamente.
                    </p>
                    
                    <div class="bg-light rounded p-4 mb-4 text-start">
                        <h6 class="mb-3"><i class="bi bi-envelope-check me-2"></i>Detalles enviados a:</h6>
                        <p class="mb-1"><strong>{{ $quote->customer_name }}</strong></p>
                        <p class="mb-0 text-muted">{{ $quote->customer_email }}</p>
                    </div>

                    <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center">
                        <a href="{{ route('quote.pdf', $quote) }}" class="btn btn-outline-primary">
                            <i class="bi bi-file-pdf me-1"></i>Descargar PDF
                        </a>
                        <a href="{{ route('catalog.index') }}" class="btn btn-primary">
                            <i class="bi bi-shop me-1"></i>Seguir Comprando
                        </a>
                    </div>
                    
                    <p class="text-muted small mt-4 mb-0">
                        <i class="bi bi-clock me-1"></i>
                        Cotización válida hasta: {{ $quote->valid_until?->format('d/m/Y') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
