<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductRequest;
use Illuminate\Http\Request;

class AdminRequestController extends Controller
{
    /**
     * Estados disponibles para las solicitudes
     * Deben coincidir con el enum de la base de datos
     */
    const STATUSES = [
        'pending' => ['label' => 'Pendiente', 'color' => 'warning', 'icon' => 'clock'],
        'contacted' => ['label' => 'Contactado', 'color' => 'info', 'icon' => 'telephone'],
        'quoted' => ['label' => 'Cotizado', 'color' => 'primary', 'icon' => 'file-text'],
        'completed' => ['label' => 'Completado', 'color' => 'success', 'icon' => 'check-circle'],
        'cancelled' => ['label' => 'Cancelado', 'color' => 'secondary', 'icon' => 'x-circle'],
    ];

    /**
     * Listar todas las solicitudes con filtros
     */
    public function index(Request $request)
    {
        $query = ProductRequest::with(['product', 'user'])
            ->orderBy('created_at', 'desc');

        // Filtro por estado
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtro por fecha
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        // Búsqueda por nombre o email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('product', function($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $requests = $query->paginate(15)->withQueryString();
        $statuses = self::STATUSES;
        
        // Conteos por estado
        $statusCounts = [
            'total' => ProductRequest::count(),
            'pending' => ProductRequest::where('status', 'pending')->count(),
            'contacted' => ProductRequest::where('status', 'contacted')->count(),
            'quoted' => ProductRequest::where('status', 'quoted')->count(),
            'completed' => ProductRequest::where('status', 'completed')->count(),
            'cancelled' => ProductRequest::where('status', 'cancelled')->count(),
        ];

        return view('admin.requests.index', compact('requests', 'statuses', 'statusCounts'));
    }

    /**
     * Ver detalle de una solicitud
     */
    public function show(ProductRequest $productRequest)
    {
        $productRequest->load(['product', 'user']);
        $statuses = self::STATUSES;
        
        return view('admin.requests.show', compact('productRequest', 'statuses'));
    }

    /**
     * Actualizar estado de la solicitud
     */
    public function updateStatus(Request $request, ProductRequest $productRequest)
    {
        $request->validate([
            'status' => 'required|in:pending,contacted,quoted,completed,cancelled'
        ]);

        $oldStatus = $productRequest->status;
        $productRequest->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Estado actualizado de "' . (self::STATUSES[$oldStatus]['label'] ?? $oldStatus) . '" a "' . self::STATUSES[$request->status]['label'] . '"');
    }

    /**
     * Guardar respuesta/notas para una solicitud
     */
    public function respond(Request $request, ProductRequest $productRequest)
    {
        $request->validate([
            'reply_message' => 'required|string|min:10|max:5000',
            'change_status' => 'nullable|in:pending,contacted,quoted,completed,cancelled'
        ]);

        // Guardar la respuesta/notas del admin
        $productRequest->update([
            'admin_reply' => $request->reply_message,
            'replied_at' => now(),
            'status' => $request->change_status ?? 'contacted'
        ]);

        return back()->with('success', 'Notas guardadas correctamente.');
    }

    /**
     * Eliminar solicitud
     */
    public function destroy(ProductRequest $productRequest)
    {
        $productRequest->delete();
        
        return redirect()->route('admin.requests.index')
            ->with('success', 'Solicitud eliminada correctamente.');
    }

    /**
     * Exportar solicitudes a CSV
     */
    public function export(Request $request)
    {
        $query = ProductRequest::with(['product', 'user'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->get();
        
        $filename = 'solicitudes_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($requests) {
            $file = fopen('php://output', 'w');
            // BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers
            fputcsv($file, ['ID', 'Fecha', 'Nombre', 'Email', 'Teléfono', 'Producto', 'Mensaje', 'Estado', 'Respuesta Admin']);
            
            foreach ($requests as $r) {
                fputcsv($file, [
                    $r->id,
                    $r->created_at->format('d/m/Y H:i'),
                    $r->name,
                    $r->email,
                    $r->phone ?? 'N/A',
                    $r->product?->name ?? 'N/A',
                    $r->message,
                    self::STATUSES[$r->status]['label'] ?? $r->status,
                    $r->admin_reply ?? ''
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
