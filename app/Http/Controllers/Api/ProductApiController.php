<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    /**
     * GET /api/products
     * Listar todos los productos con filtros
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images', 'mainImage'])
            ->where('is_active', true);

        // Búsqueda
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku_code', 'like', "%{$search}%");
            });
        }

        // Filtro por categoría
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->get('category_id'));
        }

        // Rango de precio
        if ($request->filled('price_min')) {
            $query->where('price_base', '>=', $request->get('price_min'));
        }
        if ($request->filled('price_max')) {
            $query->where('price_base', '<=', $request->get('price_max'));
        }

        // Ordenamiento
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price_base', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price_base', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->orderBy('id', 'desc');
        }

        $products = $query->paginate($request->get('per_page', 12));

        return response()->json($products, 200);
    }

    /**
     * GET /api/products/{id}
     * Obtener detalles de un producto
     */
    public function show(Product $product)
    {
        $product->load(['category', 'images', 'reviews.user']);
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'sku_code' => $product->sku_code,
                'description' => $product->description,
                'technical_specs' => $product->technical_specs,
                'price' => $product->price_base,
                'stock' => $product->stock_available,
                'is_active' => $product->is_active,
                'category' => $product->category,
                'images' => $product->images->map(fn($img) => [
                    'url' => $img->url,
                    'is_main' => $img->is_main,
                ]),
                'reviews' => $product->reviews->map(fn($review) => [
                    'id' => $review->id,
                    'user' => $review->user->name,
                    'rating' => $review->rating,
                    'title' => $review->title,
                    'comment' => $review->comment,
                    'created_at' => $review->created_at,
                ]),
                'average_rating' => round($product->averageRating(), 1),
            ],
        ], 200);
    }

    /**
     * GET /api/products/search
     * Búsqueda de productos
     */
    public function search(Request $request)
    {
        $search = $request->get('q');
        
        if (!$search || strlen($search) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Búsqueda muy corta (mínimo 2 caracteres)',
            ], 400);
        }

        $products = Product::where('is_active', true)
            ->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku_code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            })
            ->with(['mainImage', 'category'])
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'count' => $products->count(),
            'data' => $products->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'price' => $p->price_base,
                'image' => $p->mainImage?->url,
                'category' => $p->category?->name,
            ]),
        ], 200);
    }
}

