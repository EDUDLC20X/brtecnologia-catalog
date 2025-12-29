@extends('layouts.app')

@section('title', 'Gestión de Contenido')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h2 mb-1">
                        <i class="bi bi-pencil-square text-primary me-2"></i>
                        Gestión de Contenido
                    </h1>
                    <p class="text-muted mb-0">Edita el contenido de las páginas públicas del sitio</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Volver al Panel
                </a>
            </div>

            <div class="row g-4">
                @foreach($sections as $sectionKey => $sectionData)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm hover-shadow">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    @switch($sectionKey)
                                        @case('global')
                                            <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                                                <i class="bi bi-gear text-primary fs-4"></i>
                                            </div>
                                            @break
                                        @case('home')
                                            <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                                                <i class="bi bi-house text-success fs-4"></i>
                                            </div>
                                            @break
                                        @case('about')
                                            <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                                                <i class="bi bi-info-circle text-info fs-4"></i>
                                            </div>
                                            @break
                                        @case('contact')
                                            <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                                                <i class="bi bi-envelope text-warning fs-4"></i>
                                            </div>
                                            @break
                                        @case('banners')
                                            <div class="rounded-circle bg-danger bg-opacity-10 p-3 me-3">
                                                <i class="bi bi-megaphone text-danger fs-4"></i>
                                            </div>
                                            @break
                                    @endswitch
                                    <div>
                                        <h5 class="card-title mb-0">{{ $sectionData['label'] }}</h5>
                                        <small class="text-muted">{{ $sectionData['contents']->count() }} elementos</small>
                                    </div>
                                </div>

                                <ul class="list-unstyled mb-3 small text-muted">
                                    @foreach($sectionData['contents']->take(4) as $content)
                                        <li class="mb-1">
                                            <i class="bi bi-dot"></i> {{ $content->label }}
                                        </li>
                                    @endforeach
                                    @if($sectionData['contents']->count() > 4)
                                        <li class="text-primary">
                                            <i class="bi bi-three-dots"></i> 
                                            y {{ $sectionData['contents']->count() - 4 }} más
                                        </li>
                                    @endif
                                </ul>

                                <a href="{{ route('admin.content.section', $sectionKey) }}" 
                                   class="btn btn-primary btn-sm w-100">
                                    <i class="bi bi-pencil me-1"></i> Editar Sección
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Quick Actions -->
            <div class="card mt-4 border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-lightning-charge text-warning me-2"></i>
                        Acciones Rápidas
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-primary w-100">
                                <i class="bi bi-eye me-1"></i> Ver Inicio
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('about') }}" target="_blank" class="btn btn-outline-primary w-100">
                                <i class="bi bi-eye me-1"></i> Ver Acerca de
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('contact') }}" target="_blank" class="btn btn-outline-primary w-100">
                                <i class="bi bi-eye me-1"></i> Ver Contacto
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Help Section -->
            <div class="alert alert-info mt-4 border-0">
                <h6 class="alert-heading">
                    <i class="bi bi-lightbulb me-2"></i>¿Cómo funciona?
                </h6>
                <ul class="mb-0 small">
                    <li>Selecciona una sección para editar su contenido</li>
                    <li>Los cambios se reflejan inmediatamente en las páginas públicas</li>
                    <li>Puedes subir imágenes personalizadas o usar las por defecto</li>
                    <li>Usa el botón "Restaurar" para volver al valor original de cualquier campo</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-shadow {
        transition: all 0.3s ease;
    }
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
</style>
@endsection
