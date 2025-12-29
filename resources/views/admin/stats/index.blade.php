@extends('layouts.app')

@section('title', 'Panel de Estadísticas')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">
                <i class="bi bi-bar-chart-line me-2 text-primary"></i>Panel de Estadísticas
            </h1>
            <p class="text-muted mb-0">Análisis de rendimiento del catálogo</p>
        </div>
        <div class="d-flex gap-2">
            <select id="periodSelector" class="form-select form-select-sm" style="width:auto;">
                <option value="7">Últimos 7 días</option>
                <option value="30" selected>Últimos 30 días</option>
                <option value="90">Últimos 90 días</option>
            </select>
            <a href="{{ route('admin.stats.export', ['period' => 30]) }}" class="btn btn-outline-success btn-sm" id="exportBtn">
                <i class="bi bi-download me-1"></i>Exportar
            </a>
        </div>
    </div>

    {{-- Tarjetas de resumen --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-box-seam fs-2 text-primary mb-2"></i>
                    <h3 class="mb-0">{{ number_format($stats['total_products']) }}</h3>
                    <small class="text-muted">Productos</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle fs-2 text-success mb-2"></i>
                    <h3 class="mb-0">{{ number_format($stats['active_products']) }}</h3>
                    <small class="text-muted">Activos</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-envelope fs-2 text-info mb-2"></i>
                    <h3 class="mb-0">{{ number_format($stats['total_requests']) }}</h3>
                    <small class="text-muted">Solicitudes</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-clock fs-2 text-warning mb-2"></i>
                    <h3 class="mb-0">{{ number_format($stats['pending_requests']) }}</h3>
                    <small class="text-muted">Pendientes</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-people fs-2 text-secondary mb-2"></i>
                    <h3 class="mb-0">{{ number_format($stats['total_users']) }}</h3>
                    <small class="text-muted">Usuarios</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-eye fs-2 text-danger mb-2"></i>
                    <h3 class="mb-0">{{ number_format($stats['total_views']) }}</h3>
                    <small class="text-muted">Vistas Totales</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Gráficas principales --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Actividad</h5>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-primary active" data-chart="requests">Solicitudes</button>
                        <button type="button" class="btn btn-outline-primary" data-chart="views">Vistas</button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="activityChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Solicitudes por Estado</h5>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="statusChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Tops --}}
    <div class="row g-4">
        {{-- Más vistos --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
            </div>
        </div>

        {{-- Más solicitados --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-envelope-paper text-success me-2"></i>Productos Más Solicitados
                        <span class="badge bg-secondary ms-2">Últimos 30 días</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Producto</th>
                                    <th class="text-end">Solicitudes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mostRequestedProducts as $index => $product)
                                    <tr>
                                        <td>
                                            <span class="badge {{ $index < 3 ? 'bg-success' : 'bg-secondary' }}">{{ $index + 1 }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('catalog.show', $product->slug) }}" target="_blank" class="text-decoration-none">
                                                {{ Str::limit($product->name, 35) }}
                                            </a>
                                            <br><small class="text-muted">{{ $product->sku_code }}</small>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge bg-success">{{ number_format($product->requests_count) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">
                                            No hay datos de solicitudes disponibles
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let activityChart = null;
    let statusChart = null;
    let currentPeriod = 30;
    let currentType = 'requests';

    // Cargar gráfica de actividad
    async function loadActivityChart(type, period) {
        const response = await fetch(`{{ route('admin.stats.data') }}?type=${type}&period=${period}`);
        const data = await response.json();

        const ctx = document.getElementById('activityChart').getContext('2d');
        
        if (activityChart) {
            activityChart.destroy();
        }

        activityChart = new Chart(ctx, {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }

    // Cargar gráfica de estados
    async function loadStatusChart() {
        const response = await fetch(`{{ route('admin.stats.data') }}?type=requests_by_status`);
        const data = await response.json();

        const ctx = document.getElementById('statusChart').getContext('2d');
        
        if (statusChart) {
            statusChart.destroy();
        }

        statusChart = new Chart(ctx, {
            type: 'doughnut',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // Selector de período
    document.getElementById('periodSelector').addEventListener('change', function() {
        currentPeriod = this.value;
        loadActivityChart(currentType, currentPeriod);
        
        // Actualizar link de exportar
        document.getElementById('exportBtn').href = `{{ route('admin.stats.export') }}?period=${currentPeriod}`;
    });

    // Botones de tipo de gráfica
    document.querySelectorAll('[data-chart]').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('[data-chart]').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentType = this.dataset.chart;
            loadActivityChart(currentType, currentPeriod);
        });
    });

    // Cargar gráficas iniciales
    loadActivityChart(currentType, currentPeriod);
    loadStatusChart();
});
</script>
@endpush
@endsection
