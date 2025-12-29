<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ProductView;

/**
 * Middleware: TrackProductViews
 * 
 * Registra los productos visualizados por usuarios autenticados.
 * Se aplica a las rutas de detalle de producto.
 */
class TrackProductViews
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Solo rastrear si el usuario está autenticado y es cliente
        $user = $request->user();
        if ($user && !$user->isAdmin()) {
            // Obtener el producto del route binding
            $product = $request->route('product');
            
            if ($product && $product instanceof \App\Models\Product) {
                try {
                    ProductView::recordView($user->id, $product->id);
                } catch (\Exception $e) {
                    // Silenciar errores de tracking para no afectar la experiencia
                    Log::debug('Error tracking product view: ' . $e->getMessage());
                }
            }
        }
        
        return $response;
    }
}
