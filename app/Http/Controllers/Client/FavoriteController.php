<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * FavoriteController
 * 
 * Gestiona los productos favoritos de los usuarios cliente.
 * Funcionalidades:
 * - Listar favoritos
 * - Agregar/quitar de favoritos (toggle)
 * - Agregar notas a favoritos
 */
class FavoriteController extends Controller
{
    /**
     * Muestra todos los productos favoritos del usuario
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $favorites = $user->favorites()
            ->with(['product' => function($query) {
                $query->with(['mainImage', 'category']);
            }])
            ->latest()
            ->paginate(12);
        
        return view('client.favorites.index', compact('favorites'));
    }

    /**
     * Toggle favorito (agregar si no existe, quitar si existe)
     * Responde en JSON para uso con AJAX
     */
    public function toggle(Request $request, Product $product): JsonResponse
    {
        $user = $request->user();
        
        // Verificar que no sea admin
        if ($user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Los administradores no pueden usar favoritos.',
            ], 403);
        }
        
        $result = Favorite::toggle($user->id, $product->id);
        
        return response()->json([
            'success' => true,
            'action' => $result['action'],
            'favorited' => $result['favorited'],
            'message' => $result['favorited'] 
                ? 'Producto agregado a favoritos' 
                : 'Producto eliminado de favoritos',
            'favorites_count' => $user->favorites()->count(),
        ]);
    }

    /**
     * Verifica si un producto está en favoritos (para inicializar UI)
     */
    public function check(Request $request, Product $product): JsonResponse
    {
        $user = $request->user();
        
        return response()->json([
            'favorited' => Favorite::isProductFavorited($user->id, $product->id),
        ]);
    }

    /**
     * Actualiza las notas de un favorito
     */
    public function updateNotes(Request $request, Product $product): JsonResponse
    {
        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        $user = $request->user();
        
        $favorite = Favorite::where('user_id', $user->id)
                           ->where('product_id', $product->id)
                           ->first();
        
        if (!$favorite) {
            return response()->json([
                'success' => false,
                'message' => 'El producto no está en tus favoritos',
            ], 404);
        }
        
        $favorite->update(['notes' => $request->notes]);
        
        return response()->json([
            'success' => true,
            'message' => 'Notas actualizadas correctamente',
        ]);
    }

    /**
     * Elimina un favorito
     */
    public function destroy(Request $request, Product $product): JsonResponse
    {
        $user = $request->user();
        
        $deleted = Favorite::where('user_id', $user->id)
                          ->where('product_id', $product->id)
                          ->delete();
        
        return response()->json([
            'success' => $deleted > 0,
            'message' => $deleted > 0 
                ? 'Producto eliminado de favoritos'
                : 'El producto no estaba en favoritos',
            'favorites_count' => $user->favorites()->count(),
        ]);
    }

    /**
     * Verifica múltiples productos (para inicializar lista de productos)
     */
    public function checkMultiple(Request $request): JsonResponse
    {
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'integer',
        ]);

        $user = $request->user();
        
        $favoritedIds = Favorite::where('user_id', $user->id)
                                ->whereIn('product_id', $request->product_ids)
                                ->pluck('product_id')
                                ->toArray();
        
        return response()->json([
            'favorited_ids' => $favoritedIds,
        ]);
    }
}
