<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductRequest;
use App\Models\ProductView;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminStatsController extends Controller
{
    /**
     * Mostrar panel de estadísticas
     */
    public function index()
    {
        // Estadísticas generales
        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'total_requests' => ProductRequest::count(),
            'pending_requests' => ProductRequest::where('status', 'pending')->count(),
            'total_users' => User::where('is_admin', false)->count(),
            'total_views' => ProductView::count(),
        ];


        // Productos más solicitados (últimos 30 días)
        // Usamos una subconsulta para contar las solicitudes recientes
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        
        $mostRequestedProducts = Product::select('products.*')
            ->selectSub(
                ProductRequest::selectRaw('COUNT(*)')
                    ->whereColumn('product_requests.product_id', 'products.id')
                    ->where('product_requests.created_at', '>=', $thirtyDaysAgo),
                'requests_count'
            )
            ->whereExists(function ($query) use ($thirtyDaysAgo) {
                $query->select(DB::raw(1))
                    ->from('product_requests')
                    ->whereColumn('product_requests.product_id', 'products.id')
                    ->where('product_requests.created_at', '>=', $thirtyDaysAgo);
            })
            ->orderByDesc('requests_count')
            ->limit(10)
            ->get();

        return view('admin.stats.index', compact('stats', 'mostRequestedProducts'));
    }

    /**
     * Obtener datos para gráficas (API JSON)
     */
    public function getData(Request $request)
    {
        $type = $request->get('type', 'requests');
        $period = $request->get('period', 30); // días

        $data = [];

        switch ($type) {
            case 'requests':
                $data = $this->getRequestsData($period);
                break;
            case 'views':
                $data = $this->getViewsData($period);
                break;
            case 'products_by_category':
                $data = $this->getProductsByCategory();
                break;
            case 'requests_by_status':
                $data = $this->getRequestsByStatus();
                break;
        }

        return response()->json($data);
    }

    /**
     * Solicitudes por día
     */
    private function getRequestsData($days)
    {
        $startDate = Carbon::now()->subDays($days);
        
        $requests = ProductRequest::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        $labels = [];
        $values = [];
        
        for ($i = $days; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $labels[] = Carbon::parse($date)->format('d M');
            $values[] = $requests[$date] ?? 0;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Solicitudes',
                    'data' => $values,
                    'borderColor' => '#0d6efd',
                    'backgroundColor' => 'rgba(13, 110, 253, 0.1)',
                    'fill' => true,
                    'tension' => 0.4
                ]
            ]
        ];
    }

    /**
     * Vistas por día
     */
    private function getViewsData($days)
    {
        $startDate = Carbon::now()->subDays($days);
        
        $views = ProductView::selectRaw('DATE(last_viewed_at) as date, COUNT(*) as count')
            ->where('last_viewed_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        $labels = [];
        $values = [];
        
        for ($i = $days; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $labels[] = Carbon::parse($date)->format('d M');
            $values[] = $views[$date] ?? 0;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Vistas',
                    'data' => $values,
                    'borderColor' => '#198754',
                    'backgroundColor' => 'rgba(25, 135, 84, 0.1)',
                    'fill' => true,
                    'tension' => 0.4
                ]
            ]
        ];
    }

    /**
     * Productos por categoría
     */
    private function getProductsByCategory()
    {
        $categories = Product::join('categories', 'products.category_id', '=', 'categories.id')
            ->selectRaw('categories.name, COUNT(*) as count')
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('count', 'desc')
            ->limit(8)
            ->get();

        return [
            'labels' => $categories->pluck('name')->toArray(),
            'datasets' => [
                [
                    'data' => $categories->pluck('count')->toArray(),
                    'backgroundColor' => [
                        '#0d6efd',
                        '#6610f2',
                        '#6f42c1',
                        '#d63384',
                        '#dc3545',
                        '#fd7e14',
                        '#ffc107',
                        '#198754'
                    ]
                ]
            ]
        ];
    }

    /**
     * Solicitudes por estado
     */
    private function getRequestsByStatus()
    {
        $statuses = ProductRequest::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        $statusLabels = [
            'pendiente' => 'Pendiente',
            'en_proceso' => 'En Proceso',
            'respondido' => 'Respondido',
            'cerrado' => 'Cerrado',
            'pending' => 'Pendiente',
            'contacted' => 'Contactado',
            'quoted' => 'Cotizado',
            'completed' => 'Completado',
            'cancelled' => 'Cancelado'
        ];

        $statusColors = [
            'pendiente' => '#ffc107',
            'en_proceso' => '#0dcaf0',
            'respondido' => '#198754',
            'cerrado' => '#6c757d',
            'pending' => '#ffc107',
            'contacted' => '#0dcaf0',
            'quoted' => '#0d6efd',
            'completed' => '#198754',
            'cancelled' => '#dc3545'
        ];

        return [
            'labels' => $statuses->map(fn($s) => $statusLabels[$s->status] ?? $s->status)->toArray(),
            'datasets' => [
                [
                    'data' => $statuses->pluck('count')->toArray(),
                    'backgroundColor' => $statuses->map(fn($s) => $statusColors[$s->status] ?? '#6c757d')->toArray()
                ]
            ]
        ];
    }

    /**
     * Exportar reporte de estadísticas
     */
    public function export(Request $request)
    {
        $period = $request->get('period', 30);
        $startDate = Carbon::now()->subDays($period);
        
        // Datos para el reporte
        $data = [
            'period' => $period,
            'generated_at' => now()->format('d/m/Y H:i'),
            'summary' => [
                'total_views' => ProductView::where('viewed_at', '>=', $startDate)->count(),
                'total_requests' => ProductRequest::where('created_at', '>=', $startDate)->count(),
                'new_users' => User::where('created_at', '>=', $startDate)->where('is_admin', false)->count(),
            ],
            'top_viewed' => Product::withCount(['productViews' => fn($q) => $q->where('last_viewed_at', '>=', $startDate)])
                ->orderBy('product_views_count', 'desc')
                ->limit(20)
                ->get(),
            'top_requested' => Product::withCount(['productRequests' => fn($q) => $q->where('created_at', '>=', $startDate)])
                ->orderBy('product_requests_count', 'desc')
                ->limit(20)
                ->get(),
        ];

        $filename = 'reporte_estadisticas_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Resumen
            fputcsv($file, ['REPORTE DE ESTADÍSTICAS']);
            fputcsv($file, ['Generado:', $data['generated_at']]);
            fputcsv($file, ['Período:', $data['period'] . ' días']);
            fputcsv($file, []);
            
            fputcsv($file, ['RESUMEN']);
            fputcsv($file, ['Total Vistas:', $data['summary']['total_views']]);
            fputcsv($file, ['Total Solicitudes:', $data['summary']['total_requests']]);
            fputcsv($file, ['Nuevos Usuarios:', $data['summary']['new_users']]);
            fputcsv($file, []);
            
            // Top productos vistos
            fputcsv($file, ['TOP PRODUCTOS MÁS VISTOS']);
            fputcsv($file, ['SKU', 'Nombre', 'Vistas']);
            foreach ($data['top_viewed'] as $p) {
                fputcsv($file, [$p->sku_code, $p->name, $p->product_views_count]);
            }
            fputcsv($file, []);
            
            // Top productos solicitados
            fputcsv($file, ['TOP PRODUCTOS MÁS SOLICITADOS']);
            fputcsv($file, ['SKU', 'Nombre', 'Solicitudes']);
            foreach ($data['top_requested'] as $p) {
                fputcsv($file, [$p->sku_code, $p->name, $p->product_requests_count]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
