@extends('layouts.app')

@section('title', 'Editar ' . $sectionLabel)

@section('styles')
<style>
    :root {
        --admin-primary: #0f2744;
        --admin-secondary: #475569;
        --admin-accent: #3b82f6;
        --admin-success: #16a34a;
        --admin-danger: #dc2626;
        --admin-warning: #d97706;
        --admin-light: #f8fafc;
        --admin-border: #e2e8f0;
    }
    
    .content-editor-card {
        background: white;
        border: 1px solid var(--admin-border);
        border-radius: 12px;
        overflow: hidden;
    }
    
    .content-editor-card .card-body {
        padding: 1.5rem;
    }
    
    .field-container {
        background: var(--admin-light);
        border: 1px solid var(--admin-border);
        border-radius: 10px;
        padding: 1.25rem;
        margin-bottom: 1.25rem;
        transition: border-color 0.2s ease;
    }
    
    .field-container:hover {
        border-color: var(--admin-accent);
    }
    
    .field-container:last-child {
        margin-bottom: 0;
    }
    
    .field-label {
        font-weight: 600;
        color: var(--admin-primary);
        font-size: 0.95rem;
        margin-bottom: 0.25rem;
    }
    
    .field-help {
        font-size: 0.8rem;
        color: var(--admin-secondary);
        margin-bottom: 0.75rem;
    }
    
    .badge-type {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.25em 0.6em;
        border-radius: 4px;
    }
    
    .badge-type.image { background: #dbeafe; color: #1e40af; }
    .badge-type.html { background: #fef3c7; color: #92400e; }
    .badge-type.text { background: #dcfce7; color: #166534; }
    
    /* Image Upload UI */
    .image-manager {
        background: white;
        border: 2px dashed var(--admin-border);
        border-radius: 10px;
        padding: 1rem;
        transition: all 0.2s ease;
    }
    
    .image-manager:hover {
        border-color: var(--admin-accent);
    }
    
    .image-manager.has-image {
        border-style: solid;
    }
    
    .image-preview-container {
        position: relative;
        display: inline-block;
        max-width: 100%;
    }
    
    .image-preview {
        max-height: 180px;
        max-width: 100%;
        width: auto;
        height: auto;
        object-fit: contain;
        border-radius: 8px;
        border: 1px solid var(--admin-border);
        background: white;
    }
    
    .image-status-badge {
        position: absolute;
        top: 8px;
        right: 8px;
        font-size: 0.65rem;
        font-weight: 700;
        padding: 0.35em 0.65em;
        border-radius: 4px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .image-status-badge.custom {
        background: var(--admin-success);
        color: white;
    }
    
    .image-status-badge.default {
        background: var(--admin-secondary);
        color: white;
    }
    
    .image-action-indicator {
        margin-top: 0.75rem;
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .image-action-indicator.replace {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fcd34d;
    }
    
    .image-action-indicator.new {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #86efac;
    }
    
    .image-upload-zone {
        margin-top: 1rem;
        padding: 1rem;
        background: var(--admin-light);
        border-radius: 8px;
        border: 1px solid var(--admin-border);
    }
    
    .image-upload-zone label {
        font-weight: 500;
        color: var(--admin-primary);
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .image-upload-zone .form-control {
        font-size: 0.85rem;
    }
    
    .image-specs {
        font-size: 0.75rem;
        color: var(--admin-secondary);
        margin-top: 0.5rem;
    }
    
    .image-specs i {
        color: var(--admin-accent);
    }
    
    .btn-remove-image {
        font-size: 0.75rem;
        padding: 0.35rem 0.75rem;
    }
    
    /* Form controls styling */
    .form-control, .form-select {
        font-size: 0.9rem;
        border-color: var(--admin-border);
        border-radius: 8px;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--admin-accent);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    /* Action buttons */
    .btn-restore {
        font-size: 0.75rem;
        padding: 0.3rem 0.6rem;
        border-radius: 6px;
    }
    
    /* Card footer */
    .card-footer-actions {
        background: var(--admin-light);
        border-top: 1px solid var(--admin-border);
        padding: 1rem 1.5rem;
    }
    
    .btn-save {
        background: var(--admin-accent);
        border: none;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        border-radius: 8px;
    }
    
    .btn-save:hover {
        background: #2563eb;
    }
    
    /* Tips alert */
    .tips-card {
        background: #fffbeb;
        border: 1px solid #fcd34d;
        border-radius: 10px;
        padding: 1rem 1.25rem;
    }
    
    .tips-card h6 {
        color: #92400e;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .tips-card ul {
        margin: 0;
        padding-left: 1.25rem;
    }
    
    .tips-card li {
        font-size: 0.8rem;
        color: #78350f;
        margin-bottom: 0.25rem;
    }
    
    /* Preview for new images */
    .new-image-preview {
        max-height: 120px;
        max-width: 200px;
        object-fit: contain;
        border-radius: 6px;
        border: 2px solid var(--admin-success);
        margin-top: 0.5rem;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-1">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Panel Admin</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.content.index') }}" class="text-decoration-none">Gestión de Contenido</a>
                            </li>
                            <li class="breadcrumb-item active">{{ $sectionLabel }}</li>
                        </ol>
                    </nav>
                    <h1 class="h3 mb-0" style="color: var(--admin-primary);">
                        <i class="bi bi-pencil-square text-primary me-2"></i>
                        Editar {{ $sectionLabel }}
                    </h1>
                </div>
                <div class="d-flex gap-2">
                    @if(in_array($section, ['home', 'about', 'contact']))
                        <a href="{{ route('admin.content.preview', $section) }}" 
                           target="_blank" 
                           class="btn btn-outline-primary">
                            <i class="bi bi-eye me-1"></i> Vista Previa
                        </a>
                    @endif
                    <a href="{{ route('admin.content.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Volver
                    </a>
                </div>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>
                        <strong>Por favor corrige los siguientes errores:</strong>
                    </div>
                    <ul class="mb-0 small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Content Form -->
            <form action="{{ route('admin.content.section.update', $section) }}" 
                  method="POST" 
                  enctype="multipart/form-data"
                  id="contentForm">
                @csrf
                @method('PUT')

                <div class="content-editor-card">
                    <div class="card-body">
                        @foreach($contents as $content)
                            @php
                                $fieldName = str_replace('.', '_', $content->key);
                            @endphp

                            <div class="field-container" id="field-{{ $fieldName }}">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <span class="field-label">{{ $content->label }}</span>
                                            @if($content->type === 'image')
                                                <span class="badge badge-type image">
                                                    <i class="bi bi-image me-1"></i>Imagen
                                                </span>
                                            @elseif($content->type === 'html')
                                                <span class="badge badge-type html">
                                                    <i class="bi bi-code-slash me-1"></i>HTML
                                                </span>
                                            @else
                                                <span class="badge badge-type text">
                                                    <i class="bi bi-fonts me-1"></i>Texto
                                                </span>
                                            @endif
                                        </div>
                                        @if($content->help_text)
                                            <div class="field-help">
                                                <i class="bi bi-info-circle me-1"></i>{{ $content->help_text }}
                                            </div>
                                        @endif
                                    </div>
                                    @if($content->hasCustomValue())
                                        <form action="{{ route('admin.content.reset', $content->id) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('¿Restaurar al valor por defecto? Esta acción no se puede deshacer.')">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-secondary btn-restore">
                                                <i class="bi bi-arrow-counterclockwise me-1"></i>Restaurar
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                @switch($content->type)
                                    @case('text')
                                        <input type="text" 
                                               class="form-control" 
                                               id="{{ $fieldName }}"
                                               name="content_{{ $fieldName }}" 
                                               value="{{ old('content_' . $fieldName, $content->value ?? $content->default_value) }}"
                                               placeholder="{{ $content->default_value }}">
                                        @if($content->default_value)
                                            <div class="form-text small">
                                                <i class="bi bi-lightbulb me-1 text-warning"></i>
                                                Por defecto: <span class="text-muted">{{ Str::limit($content->default_value, 80) }}</span>
                                            </div>
                                        @endif
                                        @break

                                    @case('textarea')
                                        <textarea class="form-control" 
                                                  id="{{ $fieldName }}"
                                                  name="content_{{ $fieldName }}" 
                                                  rows="4"
                                                  placeholder="{{ $content->default_value }}">{{ old('content_' . $fieldName, $content->value ?? $content->default_value) }}</textarea>
                                        @break

                                    @case('html')
                                        <textarea class="form-control font-monospace" 
                                                  id="{{ $fieldName }}"
                                                  name="content_{{ $fieldName }}" 
                                                  rows="5"
                                                  style="font-size: 0.85rem;"
                                                  placeholder="{{ $content->default_value }}">{{ old('content_' . $fieldName, $content->value ?? $content->default_value) }}</textarea>
                                        <div class="form-text small mt-2">
                                            <i class="bi bi-code-slash me-1 text-primary"></i>
                                            <strong>Etiquetas permitidas:</strong> 
                                            <code>&lt;p&gt;</code>, <code>&lt;br&gt;</code>, <code>&lt;strong&gt;</code>, 
                                            <code>&lt;em&gt;</code>, <code>&lt;ul&gt;</code>, <code>&lt;ol&gt;</code>, 
                                            <code>&lt;li&gt;</code>, <code>&lt;a&gt;</code>, <code>&lt;h1-h6&gt;</code>
                                        </div>
                                        @break

                                    @case('image')
                                        @php
                                            $currentImage = $content->image_path ?? null;
                                            $defaultImage = $content->default_value ?? null;
                                            $hasCustomImage = !empty($currentImage);
                                            $displayImage = $hasCustomImage 
                                                ? content_image_url($currentImage)
                                                : ($defaultImage ? asset($defaultImage) : null);
                                        @endphp
                                        
                                        <div class="image-manager {{ $displayImage ? 'has-image' : '' }}">
                                            <div class="row">
                                                <!-- Current Image Preview -->
                                                <div class="col-md-5 mb-3 mb-md-0">
                                                    <div class="text-center">
                                                        <p class="small fw-semibold text-muted mb-2">
                                                            <i class="bi bi-image me-1"></i>Imagen Actual
                                                        </p>
                                                        @if($displayImage)
                                                            <div class="image-preview-container">
                                                                <img src="{{ $displayImage }}" 
                                                                     alt="{{ $content->label }}"
                                                                     class="image-preview"
                                                                     id="preview-{{ $fieldName }}">
                                                                <span class="image-status-badge {{ $hasCustomImage ? 'custom' : 'default' }}">
                                                                    {{ $hasCustomImage ? 'Personalizada' : 'Por defecto' }}
                                                                </span>
                                                            </div>
                                                        @else
                                                            <div class="bg-light rounded p-4 text-center text-muted" style="min-height: 120px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                                                <i class="bi bi-image fs-1 mb-2" style="opacity: 0.4;"></i>
                                                                <p class="mb-0 small">Sin imagen configurada</p>
                                                            </div>
                                                        @endif
                                                        
                                                        @if($hasCustomImage)
                                                            <form action="{{ route('admin.content.image.remove', $content->id) }}" 
                                                                  method="POST" 
                                                                  class="mt-2"
                                                                  onsubmit="return confirm('¿Eliminar imagen personalizada? Se mostrará la imagen por defecto.')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-outline-danger btn-remove-image">
                                                                    <i class="bi bi-trash me-1"></i>Eliminar
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <!-- Upload Zone -->
                                                <div class="col-md-7">
                                                    <div class="image-upload-zone">
                                                        <label for="{{ $fieldName }}">
                                                            <i class="bi bi-cloud-arrow-up"></i>
                                                            {{ $hasCustomImage ? 'Reemplazar imagen' : 'Subir nueva imagen' }}
                                                        </label>
                                                        <input type="file" 
                                                               class="form-control" 
                                                               id="{{ $fieldName }}"
                                                               name="image_{{ $fieldName }}"
                                                               accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                                               onchange="previewNewImage(this, '{{ $fieldName }}')">
                                                        
                                                        <div class="image-specs mt-2">
                                                            <i class="bi bi-info-circle me-1"></i>
                                                            <strong>Formatos:</strong> JPG, PNG, GIF, WebP &nbsp;|&nbsp; 
                                                            <strong>Máximo:</strong> 2MB
                                                        </div>
                                                        
                                                        <!-- Action indicator -->
                                                        <div id="action-{{ $fieldName }}" class="image-action-indicator d-none">
                                                            <i class="bi bi-arrow-repeat"></i>
                                                            <span></span>
                                                        </div>
                                                        
                                                        <!-- New image preview -->
                                                        <div id="new-preview-{{ $fieldName }}" class="text-center d-none mt-3">
                                                            <p class="small text-success fw-semibold mb-2">
                                                                <i class="bi bi-check-circle me-1"></i>Nueva imagen seleccionada:
                                                            </p>
                                                            <img src="" alt="Nueva imagen" class="new-image-preview" id="new-img-{{ $fieldName }}">
                                                            <button type="button" class="btn btn-sm btn-outline-secondary d-block mx-auto mt-2" onclick="clearImageSelection('{{ $fieldName }}')">
                                                                <i class="bi bi-x me-1"></i>Cancelar
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @break
                                @endswitch
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="card-footer-actions d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.content.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary btn-save">
                            <i class="bi bi-check-lg me-1"></i> Guardar Cambios
                        </button>
                    </div>
                </div>
            </form>

            <!-- Tips -->
            <div class="tips-card mt-4">
                <h6>
                    <i class="bi bi-lightbulb-fill me-2"></i>Consejos para la gestión de contenido
                </h6>
                <ul>
                    <li>Los cambios se guardan inmediatamente y se reflejan en el sitio público</li>
                    <li>Al subir una imagen, la anterior se <strong>reemplaza automáticamente</strong> (no se duplica)</li>
                    <li>Usa "Restaurar" para volver al valor original de cualquier campo</li>
                    <li>Las imágenes se optimizan automáticamente al subirse</li>
                    <li>Usa "Vista Previa" para verificar los cambios antes de publicar</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function previewNewImage(input, fieldName) {
    const actionDiv = document.getElementById('action-' + fieldName);
    const newPreviewDiv = document.getElementById('new-preview-' + fieldName);
    const newImg = document.getElementById('new-img-' + fieldName);
    const currentPreview = document.getElementById('preview-' + fieldName);
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validate file size (2MB max)
        if (file.size > 2 * 1024 * 1024) {
            alert('La imagen es demasiado grande. El tamaño máximo es 2MB.');
            input.value = '';
            return;
        }
        
        // Validate file type
        const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!validTypes.includes(file.type)) {
            alert('Formato no válido. Usa JPG, PNG, GIF o WebP.');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            newImg.src = e.target.result;
            newPreviewDiv.classList.remove('d-none');
            
            // Show action indicator
            actionDiv.classList.remove('d-none');
            if (currentPreview) {
                actionDiv.classList.add('replace');
                actionDiv.classList.remove('new');
                actionDiv.querySelector('span').textContent = 'Esta imagen reemplazará a la actual al guardar';
            } else {
                actionDiv.classList.add('new');
                actionDiv.classList.remove('replace');
                actionDiv.querySelector('span').textContent = 'Se agregará esta nueva imagen al guardar';
            }
        };
        reader.readAsDataURL(file);
    }
}

function clearImageSelection(fieldName) {
    const input = document.getElementById(fieldName);
    const actionDiv = document.getElementById('action-' + fieldName);
    const newPreviewDiv = document.getElementById('new-preview-' + fieldName);
    
    input.value = '';
    actionDiv.classList.add('d-none');
    newPreviewDiv.classList.add('d-none');
}
</script>
@endsection
