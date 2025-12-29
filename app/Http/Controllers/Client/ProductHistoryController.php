<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ProductView;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * ProductHistoryController
 * 
 * Gestiona el historial de productos visualizados por el usuario.
 * Funcionalidades:
 * - Ver historial completo
 * - Limpiar historial
 * - Obtener recomendaciones basadas en historial
 */
class ProductHistoryController extends Controller
{
    /**
     * Muestra el historial de productos visualizados
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $history = ProductView::with(['product' => function($query) {
                $query->with(['mainImage', 'category']);
            }])
            ->where('user_id', $user->id)
            ->whereHas('product', function($query) {
                $query->where('is_active', true);
            })
            ->orderByDesc('last_viewed_at')
            ->paginate(12);
        
        return view('client.history.index', compact('history'));
    }

    /**
     * Limpia todo el historial del usuario
     */
    public function clear(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $deleted = ProductView::where('user_id', $user->id)->delete();
        
        return response()->json([
            'success' => true,
            'message' => "Se eliminaron {$deleted} productos del historial",
        ]);
    }

    /**
     * Elimina un producto específico del historial
     */
    public function destroy(Request $request, int $productId): JsonResponse
    {
        $user = $request->user();
        
        $deleted = ProductView::where('user_id', $user->id)
                             ->where('product_id', $productId)
                             ->delete();
        
        return response()->json([
            'success' => $deleted > 0,
            'message' => $deleted > 0 
                ? 'Producto eliminado del historial'
                : 'El producto no estaba en tu historial',
        ]);
    }

    /**
     * Obtiene recomendaciones personalizadas basadas en el historial
     */
    public function recommendations(Request $request)
    {
        $user = $request->user();
        $limit = $request->get('limit', 8);
        
        $recommendations = ProductView::getRecommendations($user->id, min($limit, 20));
        
        if ($request->wantsJson()) {
            return response()->json([
                'recommendations' => $recommendations,
            ]);
        }
        
        return view('client.history.recommendations', compact('recommendations'));
    }
}
