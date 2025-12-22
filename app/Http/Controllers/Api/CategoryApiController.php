<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryApiController extends Controller
{
    /**
     * GET /api/categories
     * Listar todas las categorías
     */
    public function index()
    {
        $categories = Category::withCount('products')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'count' => $categories->count(),
            'data' => $categories->map(fn($cat) => [
                'id' => $cat->id,
                'name' => $cat->name,
                'slug' => $cat->slug,
                'products_count' => $cat->products_count,
            ]),
        ], 200);
    }

    /**
     * GET /api/categories/{id}/products
     * Obtener productos de una categoría
     */
    public function products(Category $category, Request $request)
    {
        $products = $category->products()
            ->where('is_active', true)
            ->with(['mainImage', 'category'])
            ->paginate($request->get('per_page', 12));

        return response()->json($products, 200);
    }
}

