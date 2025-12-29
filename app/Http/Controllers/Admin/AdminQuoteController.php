<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminQuoteController extends Controller
{
    /**
     * Listar todas las cotizaciones
     */
    public function index(Request $request)
    {
        $query = Quote::with(['items.product', 'user'])
            ->where('status', '!=', Quote::STATUS_DRAFT)
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

        // Búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('quote_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        $quotes = $query->paginate(15)->withQueryString();

        // Estadísticas
        $stats = [
            'total' => Quote::where('status', '!=', Quote::STATUS_DRAFT)->count(),
            'sent' => Quote::where('status', Quote::STATUS_SENT)->count(),
            'viewed' => Quote::where('status', Quote::STATUS_VIEWED)->count(),
            'accepted' => Quote::where('status', Quote::STATUS_ACCEPTED)->count(),
            'rejected' => Quote::where('status', Quote::STATUS_REJECTED)->count(),
        ];

        return view('admin.quotes.index', compact('quotes', 'stats'));
    }

    /**
     * Ver detalle de una cotización
     */
    public function show(Quote $quote)
    {
        $quote->load(['items.product', 'user']);
        
        return view('admin.quotes.show', compact('quote'));
    }

    /**
     * Actualizar estado de cotización
     */
    public function updateStatus(Request $request, Quote $quote)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', [
                Quote::STATUS_SENT,
                Quote::STATUS_VIEWED,
                Quote::STATUS_ACCEPTED,
                Quote::STATUS_REJECTED,
                Quote::STATUS_EXPIRED
            ])
        ]);

        $quote->update(['status' => $request->status]);

        return back()->with('success', 'Estado actualizado correctamente.');
    }

    /**
     * Descargar PDF de cotización
     */
    public function downloadPdf(Quote $quote)
    {
        $quote->load(['items.product']);
        
        $pdf = Pdf::loadView('quotes.pdf', compact('quote'));
        
        return $pdf->download('cotizacion_' . $quote->quote_number . '.pdf');
    }

    /**
     * Eliminar cotización
     */
    public function destroy(Quote $quote)
    {
        $quote->items()->delete();
        $quote->delete();

        return redirect()->route('admin.quotes.index')
            ->with('success', 'Cotización eliminada correctamente.');
    }
}
