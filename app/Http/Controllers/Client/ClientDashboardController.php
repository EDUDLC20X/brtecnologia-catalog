<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\ProductView;
use App\Models\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

/**
 * ClientDashboardController
 * 
 * Controlador principal para el panel de cliente.
 * Proporciona acceso a:
 * - Resumen de actividad del usuario
 * - Productos favoritos
 * - Historial de navegación
 * - Solicitudes realizadas
 * - Recomendaciones personalizadas
 */
class ClientDashboardController extends Controller
{
    /**
     * Muestra el dashboard principal del cliente
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Registrar actividad
        $user->recordActivity();
        
        // Obtener datos para el dashboard
        $favoritesCount = $user->favorites()->count();
        $requestsCount = $user->productRequests()->count();
        $pendingRequestsCount = $user->productRequests()->pending()->count();
        
        // Últimos 5 favoritos
        $recentFavorites = $user->favorites()
            ->with(['product.mainImage', 'product.category'])
            ->latest()
            ->limit(5)
            ->get();
        
        // Historial de productos vistos
        $recentViews = ProductView::where('user_id', $user->id)
            ->with(['product.mainImage', 'product.category'])
            ->orderByDesc('last_viewed_at')
            ->limit(5)
            ->get();
        
        // Últimas solicitudes
        $recentRequests = $user->productRequests()
            ->with('product.mainImage')
            ->latest()
            ->limit(5)
            ->get();
        
        // Recomendaciones basadas en categorías de productos vistos/favoritos
        $viewedCategoryIds = ProductView::where('user_id', $user->id)
            ->with('product')
            ->get()
            ->pluck('product.category_id')
            ->filter()
            ->unique();
        
        $favoriteCategoryIds = $user->favorites()
            ->with('product')
            ->get()
            ->pluck('product.category_id')
            ->filter()
            ->unique();
        
        $categoryIds = $viewedCategoryIds->merge($favoriteCategoryIds)->unique();
        
        // Obtener productos recomendados de esas categorías (excluyendo ya vistos/favoritos)
        $viewedProductIds = ProductView::where('user_id', $user->id)->pluck('product_id');
        $favoriteProductIds = $user->favorites()->pluck('product_id');
        $excludeIds = $viewedProductIds->merge($favoriteProductIds)->unique();
        
        $recommendations = collect();
        if ($categoryIds->isNotEmpty()) {
            $recommendations = Product::whereIn('category_id', $categoryIds)
                ->whereNotIn('id', $excludeIds)
                ->where('is_active', true)
                ->with(['mainImage', 'category'])
                ->inRandomOrder()
                ->limit(6)
                ->get();
        }

        return view('client.dashboard', compact(
            'user',
            'favoritesCount',
            'requestsCount',
            'pendingRequestsCount',
            'recentFavorites',
            'recentViews',
            'recentRequests',
            'recommendations'
        ));
    }
}
