@extends('layouts.app')
@section('title', 'Crear Categoría')

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
    .icon-selector {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(60px, 1fr));
        gap: 0.5rem;
        max-height: 300px;
        overflow-y: auto;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 8px;
        border: 2px solid #e2e8f0;
    }
    .icon-option {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 0.75rem;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        background: white;
        cursor: pointer;
        transition: all 0.2s;
        min-height: 60px;
    }
    .icon-option:hover {
        border-color: #3b82f6;
        background: #eff6ff;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .icon-option.selected {
        border-color: #3b82f6;
        background: #dbeafe;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
    }
    .icon-option i {
        font-size: 1.5rem;
        color: #374151;
    }
    .icon-option.selected i {
        color: #2563eb;
    }
    .icon-preview {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        background: #f8fafc;
        margin-bottom: 0.5rem;
    }
    .icon-preview i {
        font-size: 2rem;
        color: #374151;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="admin-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1><i class="bi bi-plus-circle me-2"></i>Crear Categoría</h1>
            <small class="opacity-75">Agrega una nueva categoría al catálogo</small>
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
                <div class="card-header">
                    <i class="bi bi-tag me-2"></i>Información de la Categoría
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.categories.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="form-label">
                                Nombre <span class="required-mark">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   placeholder="Ej: Herramientas Eléctricas"
                                   required
                                   autofocus>
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
                                      placeholder="Descripción opcional de la categoría...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="icon" class="form-label">
                                Ícono de Categoría <span class="required-mark">*</span>
                            </label>
                            <input type="hidden" name="icon" id="icon" value="{{ old('icon', 'bi-box') }}">
                            
                            <div class="icon-preview mb-3" id="iconPreview">
                                <i class="bi {{ old('icon', 'bi-box') }}"></i>
                            </div>
                            
                            <small class="text-muted d-block mb-2">Selecciona un ícono semántico que represente la categoría:</small>
                            
                            <div class="icon-selector" id="iconSelector">
                                @php
                                $categoryIcons = [
                                    // Computación general
                                    'bi-box' => 'General',
                                    'bi-laptop' => 'Laptops',
                                    'bi-phone' => 'Móviles',
                                    'bi-printer' => 'Impresoras',
                                    'bi-keyboard' => 'Teclados',
                                    'bi-mouse' => 'Mouse',
                                    'bi-headphones' => 'Audífonos',
                                    'bi-camera' => 'Cámaras',
                                    'bi-tv' => 'Televisores',
                                    'bi-display' => 'Monitores',
                                    'bi-router' => 'Routers',
                                    'bi-hdd' => 'Discos Duros',
                                    'bi-usb-drive' => 'USB',
                                    'bi-cpu' => 'Procesadores',
                                    'bi-gpu-card' => 'Tarjetas Video',
                                    'bi-speaker' => 'Parlantes',
                                    'bi-mic' => 'Micrófonos',
                                    'bi-webcam' => 'Webcams',
                                    'bi-plug' => 'Adaptadores',
                                    'bi-tools' => 'Herramientas',
                                    'bi-hammer' => 'Construcción',
                                    'bi-screwdriver' => 'Reparación',
                                    // Nuevos iconos
                                    'bi-battery-charging' => 'Baterías',
                                    'bi-lightning-charge' => 'Cargadores',
                                    'bi-lightning' => 'Energía',
                                    'bi-wifi' => 'WiFi',
                                    'bi-ethernet' => 'Cables',
                                    'bi-camera-video' => 'Cámaras Video',
                                    'bi-controller' => 'Gaming',
                                    'bi-device-ssd' => 'SSD',
                                    'bi-disc' => 'Discos',
                                    'bi-briefcase' => 'Estuches',
                                    'bi-house-gear' => 'Electrodom.',
                                    'bi-key' => 'Licencias',
                                    'bi-motherboard' => 'Mainboards',
                                    'bi-sd-card' => 'MicroSD',
                                    'bi-memory' => 'RAM',
                                    'bi-table' => 'Mesas',
                                    'bi-file-earmark' => 'Papel',
                                    'bi-projector' => 'Proyectores',
                                    'bi-shield-check' => 'Protectores',
                                    'bi-smartwatch' => 'SmartWatch',
                                    'bi-diagram-3' => 'Switches',
                                    'bi-tablet' => 'Tablets',
                                    'bi-pci-card' => 'Tarjetas Red',
                                    'bi-droplet' => 'Tintas',
                                    'bi-tv-fill' => 'TV Box',
                                    'bi-battery-full' => 'Power Station',
                                    'bi-plug-fill' => 'UPS',
                                    'bi-fan' => 'Ventiladores',
                                    'bi-alexa' => 'Amazon',
                                ];
                                @endphp
                                
                                @foreach($categoryIcons as $iconClass => $iconLabel)
                                <div class="icon-option" data-icon="{{ $iconClass }}" title="{{ $iconLabel }}">
                                    <i class="bi {{ $iconClass }}"></i>
                                </div>
                                @endforeach
                            </div>
                            
                            @error('icon')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i> Crear Categoría
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const iconInput = document.getElementById('icon');
    const iconPreview = document.getElementById('iconPreview');
    const iconOptions = document.querySelectorAll('.icon-option');
    
    // Marcar el ícono seleccionado inicialmente
    const currentIcon = iconInput.value;
    iconOptions.forEach(option => {
        if (option.dataset.icon === currentIcon) {
            option.classList.add('selected');
        }
    });
    
    // Manejar selección de ícono
    iconOptions.forEach(option => {
        option.addEventListener('click', function() {
            const selectedIcon = this.dataset.icon;
            
            // Actualizar campo oculto
            iconInput.value = selectedIcon;
            
            // Actualizar preview
            iconPreview.innerHTML = `<i class="bi ${selectedIcon}"></i>`;
            
            // Actualizar estilos de selección
            iconOptions.forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
        });
    });
});
</script>
@endpush

@endsection
