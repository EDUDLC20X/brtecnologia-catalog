@php
    $isEdit = isset($product) && $product;
    $formAction = $formAction ?? '#';
    $formMethod = $formMethod ?? ($isEdit ? 'PUT' : 'POST');
@endphp

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $isEdit ? 'Editar producto: '.$product->name : 'Crear producto' }}</h5>
                @if($isEdit)
                    <small class="text-muted">ID: {{ $product->id }}</small>
                @endif
            </div>

            <form action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(strtoupper($formMethod) !== 'POST')
                    @method($formMethod)
                @endif

                <div class="card-body">

                    {{-- Información General --}}
                    <div class="mb-4">
                        <h6 class="mb-3">Información General</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="category_id" class="form-label">Categoría</label>
                                <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror">
                                    <option value="">-- Selecciona una categoría --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-5">
                                <label for="name" class="form-label">Nombre <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nombre del producto" value="{{ old('name', $product->name ?? '') }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-3">
                                <label for="sku_code" class="form-label">SKU <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="sku_code" id="sku_code" class="form-control @error('sku_code') is-invalid @enderror" placeholder="PROD-001" value="{{ old('sku_code', $product->sku_code ?? '') }}" required>
                                </div>
                                @error('sku_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- Detalles del Producto --}}
                    <div class="mb-4">
                        <h6 class="mb-3">Detalles del Producto</h6>
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="description" class="form-label">Descripción</label>
                                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Descripción del producto">{{ old('description', $product->description ?? '') }}</textarea>
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label for="technical_specs" class="form-label">Especificaciones Técnicas</label>
                                <textarea name="technical_specs" id="technical_specs" class="form-control @error('technical_specs') is-invalid @enderror" rows="3" placeholder="RAM: 8GB; CPU: i5; Almacenamiento: 256GB SSD">{{ old('technical_specs', $product->technical_specs ?? '') }}</textarea>
                                @error('technical_specs')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- Precio y Stock --}}
                    <div class="mb-4">
                        <h6 class="mb-3">Precio y Stock</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="price_base" class="form-label">Precio base (USD)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" min="0" name="price_base" id="price_base" class="form-control @error('price_base') is-invalid @enderror" placeholder="0.00" value="{{ old('price_base', isset($product) ? number_format($product->price_base, 2, '.', '') : old('price_base', '')) }}">
                                </div>
                                @error('price_base')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label for="stock_available" class="form-label">Stock disponible</label>
                                <input type="number" step="1" min="0" name="stock_available" id="stock_available" class="form-control @error('stock_available') is-invalid @enderror" value="{{ old('stock_available', $product->stock_available ?? 0) }}">
                                @error('stock_available')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4 d-flex align-items-center">
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Activo</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Ofertas Especiales --}}
                    <div class="mb-4">
                        <h6 class="mb-3"><i class="bi bi-percent text-danger me-1"></i>Oferta Especial</h6>
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="is_on_sale" name="is_on_sale" value="1" 
                                                   {{ old('is_on_sale', $product->is_on_sale ?? false) ? 'checked' : '' }}
                                                   onchange="toggleSaleFields(this.checked)">
                                            <label class="form-check-label fw-bold" for="is_on_sale">
                                                <i class="bi bi-tag-fill text-danger me-1"></i>Activar oferta
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div id="saleFields" class="{{ old('is_on_sale', $product->is_on_sale ?? false) ? '' : 'd-none' }}">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label for="sale_price" class="form-label">Precio de oferta (USD)</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-danger text-white">$</span>
                                                    <input type="number" step="0.01" min="0" name="sale_price" id="sale_price" 
                                                           class="form-control @error('sale_price') is-invalid @enderror" 
                                                           placeholder="0.00" 
                                                           value="{{ old('sale_price', isset($product) && $product->sale_price ? number_format($product->sale_price, 2, '.', '') : '') }}">
                                                </div>
                                                @error('sale_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                <small class="text-muted">Debe ser menor al precio base</small>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="sale_starts_at" class="form-label">Inicio de oferta</label>
                                                <input type="datetime-local" name="sale_starts_at" id="sale_starts_at" 
                                                       class="form-control @error('sale_starts_at') is-invalid @enderror"
                                                       value="{{ old('sale_starts_at', isset($product) && $product->sale_starts_at ? $product->sale_starts_at->format('Y-m-d\TH:i') : '') }}">
                                                @error('sale_starts_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                <small class="text-muted">Opcional - dejar vacío para inicio inmediato</small>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="sale_ends_at" class="form-label">Fin de oferta</label>
                                                <input type="datetime-local" name="sale_ends_at" id="sale_ends_at" 
                                                       class="form-control @error('sale_ends_at') is-invalid @enderror"
                                                       value="{{ old('sale_ends_at', isset($product) && $product->sale_ends_at ? $product->sale_ends_at->format('Y-m-d\TH:i') : '') }}">
                                                @error('sale_ends_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                <small class="text-muted">Opcional - dejar vacío para oferta permanente</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Imágenes --}}
                    <div class="mb-4">
                        <h6 class="mb-3">Imágenes del Producto</h6>

                        @if($isEdit && isset($product->images) && $product->images->count())
                            <div class="mb-3 d-flex flex-wrap gap-2">
                                @foreach($product->images as $img)
                                    <div class="position-relative text-center" style="width:120px;">
                                        <img src="{{ image_url($img->path) }}" alt="imagen" class="img-thumbnail" style="width:120px;height:90px;object-fit:cover;">
                                        @if($img->is_main)
                                            <span class="badge bg-primary position-absolute" style="top:6px;left:6px;">Principal</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="mb-2">
                            <input type="file" name="images[]" id="images" class="form-control @error('images.*') is-invalid @enderror" accept="image/*" multiple>
                            <small class="form-text text-muted">Puedes seleccionar varias imágenes. La última subida será marcada como principal.</small>
                            @error('images.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>

                </div>

                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary me-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Guardar cambios' : 'Crear producto' }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleSaleFields(checked) {
    const saleFields = document.getElementById('saleFields');
    if (checked) {
        saleFields.classList.remove('d-none');
    } else {
        saleFields.classList.add('d-none');
    }
}

// Inicializar al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    const checkbox = document.getElementById('is_on_sale');
    if (checkbox) {
        toggleSaleFields(checkbox.checked);
    }
});
</script>
