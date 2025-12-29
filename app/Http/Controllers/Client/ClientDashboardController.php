<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\ProductView;
use App\Models\ProductRequest;
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
        
        
        
        // Últimas solicitudes
        $recentRequests = $user->productRequests()
            ->with('product.mainImage')
            ->latest()
            ->limit(5)
            ->get();

        return view('client.dashboard', compact(
            'user',
            'favoritesCount',
            'requestsCount',
            'pendingRequestsCount',
            'recentFavorites',
            'recentRequests'
        ));
    }
}
