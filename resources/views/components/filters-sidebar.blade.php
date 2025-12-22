<!-- Filtros Avanzados - Diseño B&R Tecnología -->
<aside class="br-filters">
    <header class="br-filters-header">
        <h5 class="mb-0"><i class="bi bi-search me-1" aria-hidden="true"></i>Filtros</h5>
    </header>
    <div class="br-filters-body">
        <div class="d-flex d-lg-none mb-3">
            <button class="btn btn-outline-primary" id="toggleFiltersBtn" aria-expanded="false" aria-controls="filtersInner">Mostrar filtros</button>
        </div>

        @php $formAction = $action ?? route('catalog.index'); @endphp
        <form action="{{ $formAction }}" method="GET" id="filterForm">
            <!-- Búsqueda movida a un offcanvas separado -->

            {{-- Filtro de ofertas --}}
            <div class="mb-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="on_sale" id="on_sale" value="1" {{ request('on_sale') ? 'checked' : '' }}>
                    <label class="form-check-label" for="on_sale">
                        <strong class="text-danger"><i class="bi bi-percent me-1"></i>Solo ofertas</strong>
                    </label>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label"><strong>Categoría</strong></label>
                <select name="categories[]" class="form-select br-select2" multiple="multiple" data-placeholder="Selecciona categorías">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ in_array($category->id, (array) request('categories', [])) ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label"><strong>Precio</strong></label>
                <div class="d-flex gap-2 mt-2">
                    <input type="number" name="price_min" id="priceMin" class="form-control" placeholder="Mín" value="{{ request('price_min', $priceRange->min_price ?? 0) }}">
                    <input type="number" name="price_max" id="priceMax" class="form-control" placeholder="Máx" value="{{ request('price_max', $priceRange->max_price ?? 0) }}">
                </div>
                <small class="text-muted d-block mt-2">Rango disponible: ${{ number_format($priceRange->min_price,2) }} - ${{ number_format($priceRange->max_price,2) }}</small>
            </div>

            <div class="mb-3">
                <label class="form-label"><strong>Disponibilidad</strong></label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="availability" id="avail_all" value="all" {{ request('availability','all')=='all' ? 'checked' : '' }}>
                    <label class="form-check-label" for="avail_all">Todos</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="availability" id="avail_in" value="in" {{ request('availability')=='in' ? 'checked' : '' }}>
                    <label class="form-check-label" for="avail_in">En stock</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="availability" id="avail_out" value="out" {{ request('availability')=='out' ? 'checked' : '' }}>
                    <label class="form-check-label" for="avail_out">Agotados</label>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label"><strong>Ordenar Por</strong></label>
                <select name="sort" class="form-select">
                    <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Fecha (Más recientes)</option>
                    <option value="date_asc" {{ request('sort') === 'date_asc' ? 'selected' : '' }}>Fecha (Más antiguas)</option>
                    <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Nombre (A-Z)</option>
                    <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Nombre (Z-A)</option>
                    <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Precio (Menor)</option>
                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Precio (Mayor)</option>
                </select>
            </div>

            <div class="d-grid gap-2 filters-actions">
                <button type="submit" class="btn btn-apply-filters">
                    <i class="bi bi-check-lg me-1"></i>Aplicar Filtros
                </button>
                <a href="{{ $formAction }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg me-1"></i>Limpiar Filtros
                </a>
            </div>
        </form>

        <!-- Assets: Select2 (noUiSlider removed — price uses simple inputs now) -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Initialize Select2 for brand and categories
                const selects = document.querySelectorAll('.br-select2');
                selects.forEach(function(el){
                    $(el).select2({
                        theme: 'classic',
                        width: '100%',
                        placeholder: el.dataset.placeholder || ''
                    });
                });

                // Price inputs are plain number fields now (no slider)
            });
        </script>
        
        <style>
            /* Filter panel styles */
            .br-filters{background:var(--white); border-radius:12px; box-shadow:0 10px 30px rgba(2,6,23,0.04); overflow:hidden}
            .br-filters-header{background:linear-gradient(90deg,var(--br-dark),#0b2f55); color:var(--white); padding:1rem 1rem}
            .br-filters-header h5{margin:0; font-weight:600}
            .br-filters-body{padding:1rem}

            .br-filters .form-label strong{color:var(--br-dark)}
            .br-filters .form-control, .br-filters .form-select{border-radius:8px}

            /* Botón Aplicar Filtros destacado */
            .btn-apply-filters {
                background: linear-gradient(135deg, #f58220 0%, #e67210 100%);
                border: none;
                color: white;
                font-weight: 600;
                padding: 0.875rem 1.5rem;
                font-size: 1rem;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(245, 130, 32, 0.35);
                transition: all 0.2s ease;
            }
            .btn-apply-filters:hover {
                background: linear-gradient(135deg, #e67210 0%, #d56200 100%);
                color: white;
                transform: translateY(-1px);
                box-shadow: 0 6px 16px rgba(245, 130, 32, 0.45);
            }
            
            .filters-actions {
                margin-top: 1.5rem;
                padding-top: 1rem;
                border-top: 1px solid #e6eef9;
            }

            /* Select2 theme tweaks */
            .select2-container .select2-selection--multiple, .select2-container .select2-selection--single{border-radius:8px; border-color:#e6eef9}
            .select2-container--classic .select2-selection--single .select2-selection__rendered{color:var(--br-dark)}

            /* Small helper spacing */
            .br-filters .form-check{margin-bottom:.4rem}
        </style>
    </div>
    </div>
</aside>
