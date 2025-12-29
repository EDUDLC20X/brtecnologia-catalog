<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\Product;
use App\Mail\QuoteMail;
use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class QuoteController extends Controller
{
    /**
     * Mostrar el carrito de cotización actual
     */
    public function index(Request $request)
    {
        $quote = $this->getCurrentQuote($request);
        
        if ($quote) {
            $quote->load(['items.product.mainImage', 'items.product.category']);
        }
        
        return view('quotes.index', compact('quote'));
    }

    /**
     * Agregar producto al carrito de cotización
     */
    public function addItem(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'nullable|integer|min:1|max:9999',
        ]);

        $quote = $this->getOrCreateQuote($request);
        $quantity = $request->input('quantity', 1);

        // Verificar si el producto ya está en la cotización
        $existingItem = $quote->items()->where('product_id', $product->id)->first();

        if ($existingItem) {
            $existingItem->update([
                'quantity' => $existingItem->quantity + $quantity,
            ]);
            $message = 'Cantidad actualizada en la cotización';
        } else {
            QuoteItem::create([
                'quote_id' => $quote->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $product->price_base,
            ]);
            $message = 'Producto agregado a la cotización';
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'items_count' => $quote->items()->count(),
                'total' => number_format($quote->fresh()->total, 2),
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Actualizar cantidad de un item
     */
    public function updateItem(Request $request, QuoteItem $item)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:9999',
        ]);

        // Verificar que el item pertenece al quote del usuario
        $quote = $this->getCurrentQuote($request);
        if (!$quote || $item->quote_id !== $quote->id) {
            return response()->json(['success' => false, 'message' => 'Item no encontrado'], 404);
        }

        $item->update(['quantity' => $request->quantity]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'item_subtotal' => number_format($item->fresh()->subtotal, 2),
                'subtotal' => number_format($quote->fresh()->subtotal, 2),
                'tax' => number_format($quote->fresh()->tax, 2),
                'total' => number_format($quote->fresh()->total, 2),
            ]);
        }

        return redirect()->back()->with('success', 'Cantidad actualizada');
    }

    /**
     * Eliminar item de la cotización
     */
    public function removeItem(Request $request, QuoteItem $item)
    {
        $quote = $this->getCurrentQuote($request);
        if (!$quote || $item->quote_id !== $quote->id) {
            return response()->json(['success' => false, 'message' => 'Item no encontrado'], 404);
        }

        $item->delete();

        if ($request->expectsJson()) {
            $quote = $quote->fresh();
            return response()->json([
                'success' => true,
                'message' => 'Producto eliminado de la cotización',
                'items_count' => $quote->items()->count(),
                'subtotal' => number_format($quote->subtotal, 2),
                'tax' => number_format($quote->tax, 2),
                'total' => number_format($quote->total, 2),
            ]);
        }

        return redirect()->back()->with('success', 'Producto eliminado de la cotización');
    }

    /**
     * Vaciar toda la cotización
     */
    public function clear(Request $request)
    {
        $quote = $this->getCurrentQuote($request);
        
        if ($quote) {
            $quote->items()->delete();
            $quote->delete();
            $request->session()->forget('quote_id');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cotización vaciada',
            ]);
        }

        return redirect()->route('quote.index')->with('success', 'Cotización vaciada');
    }

    /**
     * Formulario para enviar la cotización
     */
    public function checkout(Request $request)
    {
        $quote = $this->getCurrentQuote($request);
        
        if (!$quote || $quote->items()->count() === 0) {
            return redirect()->route('quote.index')->with('error', 'Tu cotización está vacía');
        }

        $quote->load(['items.product.mainImage', 'items.product.category']);
        
        return view('quotes.checkout', compact('quote'));
    }

    /**
     * Enviar la cotización por email
     */
    public function send(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_email' => 'required|email|max:100',
            'customer_phone' => 'nullable|string|max:20',
            'customer_company' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);

        $quote = $this->getCurrentQuote($request);
        
        if (!$quote || $quote->items()->count() === 0) {
            return redirect()->route('quote.index')->with('error', 'Tu cotización está vacía');
        }

        // Actualizar datos del cliente
        $quote->update([
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'customer_company' => $request->customer_company,
            'notes' => $request->notes,
            'status' => Quote::STATUS_SENT,
            'sent_at' => now(),
            'valid_until' => now()->addDays(15),
        ]);

        // Enviar email al cliente
        try {
            MailService::send(
                $quote->customer_email,
                new QuoteMail($quote, 'customer'),
                'quote-customer-' . $quote->id
            );
        } catch (\Exception $e) {
            Log::error('Error enviando cotización al cliente: ' . $e->getMessage());
        }

        // Enviar email al admin
        $adminEmail = config('mail.admin_email', config('mail.from.address'));
        if ($adminEmail) {
            try {
                MailService::send(
                    $adminEmail,
                    new QuoteMail($quote, 'admin'),
                    'quote-admin-' . $quote->id
                );
            } catch (\Exception $e) {
                Log::error('Error enviando cotización al admin: ' . $e->getMessage());
            }
        }

        // Limpiar sesión
        $request->session()->forget('quote_id');

        return redirect()->route('quote.success', $quote)->with('success', '¡Tu cotización ha sido enviada!');
    }

    /**
     * Página de éxito después de enviar cotización
     */
    public function success(Quote $quote)
    {
        return view('quotes.success', compact('quote'));
    }

    /**
     * Generar y descargar PDF de la cotización
     */
    public function downloadPdf(Quote $quote)
    {
        $quote->load(['items.product.mainImage', 'items.product.category']);
        
        $pdf = Pdf::loadView('quotes.pdf', compact('quote'));
        
        return $pdf->download('cotizacion-' . $quote->quote_number . '.pdf');
    }

    /**
     * Ver cotización pública (link compartido)
     */
    public function view(Quote $quote)
    {
        if ($quote->status === Quote::STATUS_SENT) {
            $quote->update(['status' => Quote::STATUS_VIEWED]);
        }

        $quote->load(['items.product.mainImage', 'items.product.category']);
        
        return view('quotes.view', compact('quote'));
    }

    /**
     * Obtener la cotización actual de la sesión
     */
    protected function getCurrentQuote(Request $request): ?Quote
    {
        $quoteId = $request->session()->get('quote_id');
        
        if (!$quoteId) {
            return null;
        }

        return Quote::where('id', $quoteId)
            ->where('status', Quote::STATUS_DRAFT)
            ->first();
    }

    /**
     * Obtener o crear una cotización
     */
    protected function getOrCreateQuote(Request $request): Quote
    {
        $quote = $this->getCurrentQuote($request);
        
        if (!$quote) {
            $user = $request->user();
            
            $quote = Quote::create([
                'user_id' => $user?->id,
                'customer_name' => $user?->name ?? '',
                'customer_email' => $user?->email ?? '',
                'customer_phone' => $user?->phone ?? '',
                'status' => Quote::STATUS_DRAFT,
            ]);
            
            $request->session()->put('quote_id', $quote->id);
        }

        return $quote;
    }

    /**
     * API: Obtener cantidad de items en la cotización
     * Solo responde a peticiones AJAX
     */
    public function getCount(Request $request)
    {
        // Si no es una petición AJAX, redirigir al home
        if (!$request->expectsJson() && !$request->ajax()) {
            return redirect()->route('home');
        }
        
        $quote = $this->getCurrentQuote($request);
        
        return response()->json([
            'count' => $quote ? $quote->items()->count() : 0,
        ]);
    }
}
