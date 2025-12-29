@extends('layouts.app')

@section('title', 'Solicitud #' . $productRequest->id)

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.requests.index') }}" class="text-muted text-decoration-none mb-2 d-inline-block">
                <i class="bi bi-arrow-left me-1"></i>Volver a solicitudes
            </a>
            <h1 class="h3 mb-0">
                <i class="bi bi-envelope-paper me-2 text-primary"></i>Solicitud #{{ $productRequest->id }}
            </h1>
        </div>
        @php
            $status = $statuses[$productRequest->status] ?? ['label' => $productRequest->status, 'color' => 'secondary', 'icon' => 'question'];
        @endphp
        <span class="badge bg-{{ $status['color'] }} fs-6 px-3 py-2">
            <i class="bi bi-{{ $status['icon'] }} me-1"></i>{{ $status['label'] }}
        </span>
    </div>

    <div class="row g-4">
        {{-- Columna izquierda: Detalles --}}
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
                            <p class="mb-0 fw-semibold">{{ $productRequest->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Email</label>
                            <p class="mb-0">
                                <a href="mailto:{{ $productRequest->email }}">{{ $productRequest->email }}</a>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Teléfono</label>
                            <p class="mb-0">{{ $productRequest->phone ?? 'No proporcionado' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Empresa</label>
                            <p class="mb-0">{{ $productRequest->company ?? 'No proporcionada' }}</p>
                        </div>
                        @if($productRequest->user)
                            <div class="col-12">
                                <span class="badge bg-info">
                                    <i class="bi bi-person-check me-1"></i>Usuario registrado (ID: {{ $productRequest->user_id }})
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Producto --}}
            @if($productRequest->product)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-box me-2"></i>Producto Solicitado</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            @if($productRequest->product->mainImage)
                                <img src="{{ image_url($productRequest->product->mainImage->path) }}" 
                                     alt="{{ $productRequest->product->name }}"
                                     class="me-3 rounded" 
                                     style="width:80px; height:80px; object-fit:contain;">
                            @endif
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $productRequest->product->name }}</h6>
                                <p class="text-muted small mb-1">SKU: {{ $productRequest->product->sku_code }}</p>
                                <p class="text-primary fw-bold mb-0">${{ number_format($productRequest->product->price_base, 2) }}</p>
                            </div>
                            <div>
                                <a href="{{ route('catalog.show', $productRequest->product->slug) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-box-arrow-up-right me-1"></i>Ver
                                </a>
                            </div>
                        </div>
                        @if($productRequest->quantity)
                            <div class="mt-3 pt-3 border-top">
                                <strong>Cantidad solicitada:</strong> {{ $productRequest->quantity }} unidades
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Mensaje del cliente --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-chat-text me-2"></i>Mensaje del Cliente</h5>
                </div>
                <div class="card-body">
                    <div class="bg-light rounded p-3">
                        {!! nl2br(e($productRequest->message)) !!}
                    </div>
                    <div class="mt-3 text-muted small">
                        <i class="bi bi-clock me-1"></i>Enviado el {{ $productRequest->created_at->format('d/m/Y \a \l\a\s H:i') }}
                    </div>
                </div>
            </div>

            {{-- Notas anteriores si existen --}}
            @if($productRequest->admin_reply)
                <div class="card shadow-sm border-start border-success border-4 mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 text-success"><i class="bi bi-journal-check me-2"></i>Notas del Administrador</h5>
                    </div>
                    <div class="card-body">
                        <div class="bg-success-subtle rounded p-3">
                            {!! nl2br(e($productRequest->admin_reply)) !!}
                        </div>
                        @if($productRequest->replied_at)
                            <div class="mt-3 text-muted small">
                                <i class="bi bi-check2-all me-1"></i>Respondido el {{ $productRequest->replied_at->format('d/m/Y \a \l\a\s H:i') }}
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Formulario de notas/respuesta --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-journal-text me-2"></i>{{ $productRequest->admin_reply ? 'Actualizar Notas' : 'Agregar Notas' }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.requests.respond', $productRequest) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="reply_message" class="form-label">Notas internas / Respuesta</label>
                            <textarea name="reply_message" id="reply_message" rows="6" 
                                      class="form-control @error('reply_message') is-invalid @enderror"
                                      placeholder="Escribe notas sobre esta solicitud..."
                                      required>{{ old('reply_message', $productRequest->admin_reply) }}</textarea>
                            @error('reply_message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Estas notas son solo para uso interno del administrador.</small>
                        </div>
                        <div class="mb-3">
                            <label for="change_status" class="form-label">Cambiar estado después de responder</label>
                            <select name="change_status" id="change_status" class="form-select">
                                <option value="contacted" selected>Contactado</option>
                                @foreach($statuses as $key => $st)
                                    @if($key != 'contacted')
                                        <option value="{{ $key }}">{{ $st['label'] }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Guardar Notas
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Columna derecha: Acciones --}}
        <div class="col-lg-4">
            {{-- Cambiar Estado --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="bi bi-arrow-repeat me-2"></i>Cambiar Estado</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @foreach($statuses as $key => $st)
                            <form action="{{ route('admin.requests.status', $productRequest) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="{{ $key }}">
                                <button type="submit" 
                                        class="btn btn-{{ $key == $productRequest->status ? '' : 'outline-' }}{{ $st['color'] }} w-100"
                                        {{ $key == $productRequest->status ? 'disabled' : '' }}>
                                    <i class="bi bi-{{ $st['icon'] }} me-2"></i>{{ $st['label'] }}
                                    @if($key == $productRequest->status)
                                        <i class="bi bi-check2 ms-2"></i>
                                    @endif
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Info rápida --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Información</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <small class="text-muted d-block">ID de Solicitud</small>
                            <strong>#{{ $productRequest->id }}</strong>
                        </li>
                        <li class="mb-2">
                            <small class="text-muted d-block">Fecha de Creación</small>
                            <strong>{{ $productRequest->created_at->format('d/m/Y H:i') }}</strong>
                        </li>
                        <li class="mb-2">
                            <small class="text-muted d-block">Última Actualización</small>
                            <strong>{{ $productRequest->updated_at->format('d/m/Y H:i') }}</strong>
                        </li>
                        @if($productRequest->replied_at)
                            <li class="mb-0">
                                <small class="text-muted d-block">Fecha de Respuesta</small>
                                <strong class="text-success">{{ $productRequest->replied_at->format('d/m/Y H:i') }}</strong>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            {{-- Acciones peligrosas --}}
            <div class="card border-0 shadow-sm border-danger">
                <div class="card-header bg-white">
                    <h6 class="mb-0 text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Zona de Peligro</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.requests.destroy', $productRequest) }}" method="POST"
                          onsubmit="return confirm('¿Estás seguro de eliminar esta solicitud? Esta acción no se puede deshacer.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bi bi-trash me-1"></i>Eliminar Solicitud
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
