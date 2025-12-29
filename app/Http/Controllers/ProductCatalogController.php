<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Product;
use App\Models\Category;

class ProductCatalogController extends Controller
{
    /**
     * Tiempo de caché en segundos (1 hora)
     */
    private const CACHE_TTL = 3600;

    /**
     * Vista principal del catálogo.
     * Si no hay filtros activos, muestra productos organizados por categoría.
     * Si hay filtros (búsqueda, categoría, precio), muestra la vista filtrada.
     */
    public function index(Request $request)
    {
        // Cachear categorías
        $categories = Cache::remember('catalog_categories', self::CACHE_TTL, function () {
            return Category::withCount('products')->orderBy('name')->get();
        });

        // Cachear rango de precios
        $priceRange = Cache::remember('catalog_price_range', self::CACHE_TTL, function () {
            return Product::where('is_active', true)
                ->selectRaw('MIN(price_base) as min_price, MAX(price_base) as max_price')
                ->first();
        });

        // Brands no se usa actualmente, pero mantenemos la variable para compatibilidad
        $brands = collect();

        // Verificar si hay filtros activos
        $hasFilters = $request->filled('search') || 
                      $request->filled('categories') || 
                      $request->filled('price_min') || 
                      $request->filled('price_max') ||
                      $request->filled('sort') ||
                      $request->filled('on_sale');

        // Si NO hay filtros activos, mostrar vista organizada por categorías
        if (!$hasFilters && !$request->ajax()) {
            return $this->indexByCategory($request, $categories, $priceRange, $brands);
        }

        // Si hay filtros, usar la lógica tradicional de filtrado
        return $this->indexFiltered($request, $categories, $priceRange, $brands);
    }

    /**
     * Vista del catálogo organizada por categorías (sin filtros).
     * Similar a tecnomegastore.ec - muestra categorías destacadas y secciones de productos.
     */
    private function indexByCategory(Request $request, $categories, $priceRange, $brands)
    {
        // Obtener productos en oferta activa
        $productsOnSale = Cache::remember('catalog_products_on_sale', self::CACHE_TTL / 4, function () {
            return Product::with(['mainImage'])
                ->where('is_active', true)
                ->onSale()
                ->orderByDesc('created_at')
                ->limit(10)
                ->get();
        });

        // Obtener categorías con productos (las más populares primero)
        $categoriesWithProducts = Cache::remember('catalog_categories_with_products', self::CACHE_TTL / 2, function () {
            return Category::withCount('products')
                ->whereHas('products', function($query) {
                    $query->where('is_active', true);
                })
                ->orderByDesc('products_count')
                ->get()
                ->map(function ($category) {
                    // Cargar los primeros 8 productos de cada categoría
                    $category->featuredProducts = Product::with(['mainImage'])
                        ->where('category_id', $category->id)
                        ->where('is_active', true)
                        ->orderByDesc('created_at')
                        ->limit(8)
                        ->get();
                    return $category;
                })
                ->filter(function ($category) {
                    return $category->featuredProducts->count() > 0;
                });
        });

        // Si por algún estado previo el cache quedó vacío, forzar recálculo inmediato
        if ($categoriesWithProducts->isEmpty()) {
            Cache::forget('catalog_categories_with_products');
            $categoriesWithProducts = Category::withCount('products')
                ->whereHas('products', function($query) {
                    $query->where('is_active', true);
                })
                ->orderByDesc('products_count')
                ->get()
                ->map(function ($category) {
                    $category->featuredProducts = Product::with(['mainImage'])
                        ->where('category_id', $category->id)
                        ->where('is_active', true)
                        ->orderByDesc('created_at')
                        ->limit(8)
                        ->get();
                    return $category;
                })
                ->filter(function ($category) {
                    return $category->featuredProducts->count() > 0;
                });
        }

        return view('catalog.index', [
            'categories' => $categories,
            'priceRange' => $priceRange,
            'brands' => $brands,
            'categoriesWithProducts' => $categoriesWithProducts,
            'productsOnSale' => $productsOnSale,
            'viewMode' => 'by_category',
        ]);
    }

    /**
     * Vista del catálogo con filtros aplicados.
     */
    private function indexFiltered(Request $request, $categories, $priceRange, $brands)
    {
        $query = Product::with(['category','images','mainImage'])->where('is_active', true);
        
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku_code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtro por productos en oferta
        if ($request->filled('on_sale') && $request->get('on_sale')) {
            $query->onSale();
        }

        // Filtro por categoría (múltiples)
        $selectedCategories = $request->get('categories', []);
        if (is_string($selectedCategories)) {
            $selectedCategories = [$selectedCategories];
        }
        $selectedCategories = array_filter(array_map('intval', (array)$selectedCategories));
        
        if (!empty($selectedCategories)) {
            $query->whereIn('category_id', $selectedCategories);
        }

        // Filtro por rango de precio
        if ($request->filled('price_min')) {
            $query->where('price_base', '>=', floatval($request->get('price_min', 0)));
        }
        if ($request->filled('price_max')) {
            $query->where('price_base', '<=', floatval($request->get('price_max', 999999)));
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
            case 'date_asc':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->cursorPaginate(12);

        // Si es AJAX, retornar solo el grid
        if ($request->ajax()) {
            return view('catalog.partials.products_grid', compact('products'))->render();
        }

        return view('catalog.index', [
            'products' => $products,
            'categories' => $categories,
            'priceRange' => $priceRange,
            'brands' => $brands,
            'viewMode' => 'filtered',
        ]);
    }

    public function show(Product $product)
    {
        $product->load('images','mainImage','category');
        $relatedProducts = Product::where('category_id', $product->category_id)
                                  ->where('id', '!=', $product->id)
                                  ->where('is_active', true)
                                  ->limit(8)
                                  ->get();

        return view('catalog.show', compact('product','relatedProducts'));
    }
}