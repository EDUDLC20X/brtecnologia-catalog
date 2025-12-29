<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\IdempotencyKey;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ProductsExportJob;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $query = Product::with(['category','images','mainImage']);

        // Búsqueda por nombre, SKU o descripción
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku_code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtro por categoría (soporta array 'categories[]' o singular 'category')
        $selectedCategories = $request->get('categories', []);
        if (is_string($selectedCategories)) {
            $selectedCategories = [$selectedCategories];
        }
        $selectedCategories = array_filter($selectedCategories);
        
        if (!empty($selectedCategories)) {
            $query->whereIn('category_id', $selectedCategories);
        } elseif ($request->filled('category')) {
            $cat = intval($request->get('category'));
            if ($cat > 0) {
                $query->where('category_id', $cat);
            }
        }

        // Filtro por rango de precios
        if ($request->filled('price_min')) {
            $query->where('price_base', '>=', floatval($request->get('price_min')));
        }
        if ($request->filled('price_max')) {
            $query->where('price_base', '<=', floatval($request->get('price_max')));
        }

        // Filtro por disponibilidad
        if ($request->filled('availability')) {
            $availability = $request->get('availability');
            if ($availability === 'in') {
                $query->where('stock_available', '>', 0);
            } elseif ($availability === 'out') {
                $query->where('stock_available', '<=', 0);
            }
        }

        // Filtro solo ofertas
        if ($request->filled('on_sale') && $request->get('on_sale')) {
            $query->onSale();
        }

        // Ordenamiento
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price_base', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price_base', 'desc');
                break;
            case 'date_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Admin listing uses normal pagination
        $products = $query->paginate(20)->withQueryString();

        $categories = Category::orderBy('name')->get();

        // Price range for filter (admin uses same filter UI)
        $priceRange = Product::selectRaw('MIN(price_base) as min_price, MAX(price_base) as max_price')->first();

        // Brands no se usa actualmente
        $brands = collect();

        return view('admin.products.index', compact('products','categories','priceRange','brands'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Early idempotency check to avoid validation against already-created records
        $idem = $request->get('idempotency_key');
        if ($idem) {
            $existing = IdempotencyKey::where('key', $idem)->first();
            if ($existing) {
                return response()->json(['message' => 'Duplicate request'], 409);
            }
        }

        $data = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'sku_code' => 'required|string|unique:products,sku_code',
            'name' => ['required','string', Rule::unique('products')->where(function ($query) use ($request) {
                $cat = $request->get('category_id');
                if ($cat) $query->where('category_id', $cat);
            })],
            'description' => 'nullable|string',
            'technical_specs' => 'nullable|string',
            'stock_available' => 'nullable|integer|min:0',
            'price_base' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
            'images.*' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
            'idempotency_key' => 'nullable|string|max:191',
        ]);

        // Idempotency handling (we already checked early, keep value if present)
        $idem = $data['idempotency_key'] ?? $idem ?? null;

        // Create product inside transaction and store idempotency key
        $product = null;
        DB::transaction(function () use ($data, $idem, &$product) {
            $product = Product::create([
                'category_id' => $data['category_id'] ?? null,
                'sku_code' => $data['sku_code'],
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'technical_specs' => $data['technical_specs'] ?? null,
                'stock_available' => $data['stock_available'] ?? 0,
                'price_base' => $data['price_base'] ?? 0,
                'is_active' => (bool) ($data['is_active'] ?? false),
            ]);

            if ($idem) {
                IdempotencyKey::create(['key' => $idem, 'product_id' => $product->id]);
            }
        });

        if ($request->hasFile('images')) {
            $cloudinary = app(CloudinaryService::class);
            
            foreach ($request->file('images') as $idx => $file) {
                $path = null;
                $cloudinaryPublicId = null;
                
                // Try Cloudinary first if configured
                if ($cloudinary->isConfigured()) {
                    $result = $cloudinary->upload($file, 'brtecnologia/products');
                    if ($result) {
                        $path = $result['url'];
                        $cloudinaryPublicId = $result['public_id'];
                    }
                }
                
                // Fallback to local storage
                if (!$path) {
                    $path = $file->store('products', 'public');
                }
                
                $product->images()->create([
                    'path' => $path,
                    'cloudinary_public_id' => $cloudinaryPublicId,
                    // La última imagen subida será la principal
                    'is_main' => $idx === count($request->file('images')) - 1,
                ]);
            }
        }

        // If request expects JSON, return created resource
        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'product_id' => $product->id], 201);
        }

        return redirect()->route('products.index')->with('success','Producto creado.');
}

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.edit', compact('product','categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'sku_code' => 'required|string|unique:products,sku_code,'.$product->id,
            'name' => ['required','string', Rule::unique('products')->where(function ($query) use ($request, $product) {
                $cat = $request->get('category_id') ?? $product->category_id;
                $query->where('category_id', $cat)->where('id','!=',$product->id);
            })],
            'description' => 'nullable|string',
            'technical_specs' => 'nullable|string',
            'stock_available' => 'nullable|integer|min:0',
            'price_base' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
            // Campos de ofertas
            'is_on_sale' => 'nullable|boolean',
            'sale_price' => 'nullable|numeric|min:0',
            'sale_starts_at' => 'nullable|date',
            'sale_ends_at' => 'nullable|date|after_or_equal:sale_starts_at',
            'images.*' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
        ]);

        $product->update([
            'category_id' => $data['category_id'] ?? null,
            'sku_code' => $data['sku_code'],
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'technical_specs' => $data['technical_specs'] ?? null,
            'stock_available' => $data['stock_available'] ?? 0,
            'price_base' => $data['price_base'] ?? 0,
            'is_active' => (bool) ($data['is_active'] ?? false),
            // Campos de ofertas
            'is_on_sale' => (bool) ($data['is_on_sale'] ?? false),
            'sale_price' => $data['sale_price'] ?? null,
            'sale_starts_at' => $data['sale_starts_at'] ?? null,
            'sale_ends_at' => $data['sale_ends_at'] ?? null,
        ]);

    if ($request->hasFile('images')) {
        $cloudinary = app(CloudinaryService::class);
        
        // Eliminar archivos y registros de imágenes previas (se reemplazan)
        foreach ($product->images as $oldImg) {
            // Delete from Cloudinary if it was stored there
            if ($oldImg->cloudinary_public_id) {
                $cloudinary->delete($oldImg->cloudinary_public_id);
            } elseif ($oldImg->path && !str_starts_with($oldImg->path, 'http')) {
                Storage::disk('public')->delete($oldImg->path);
            }
            $oldImg->delete();
        }

        foreach ($request->file('images') as $idx => $file) {
            $path = null;
            $cloudinaryPublicId = null;
            
            // Try Cloudinary first if configured
            if ($cloudinary->isConfigured()) {
                $result = $cloudinary->upload($file, 'brtecnologia/products');
                if ($result) {
                    $path = $result['url'];
                    $cloudinaryPublicId = $result['public_id'];
                }
            }
            
            // Fallback to local storage
            if (!$path) {
                $path = $file->store('products', 'public');
            }
            
            $product->images()->create([
                'path' => $path,
                'cloudinary_public_id' => $cloudinaryPublicId,
                // La última imagen subida será la principal
                'is_main' => $idx === count($request->file('images')) - 1,
            ]);
        }
    }

    return redirect()->route('products.index')->with('success','Producto actualizado.');
}

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index');
    }

    public function show(Product $product)
    {
        // Admin show: redirect to edit page for now
        return redirect()->route('products.edit', $product->id);
    }

    // Dispatch export job (admin)
    public function export(\Illuminate\Http\Request $request)
    {
        // Ensure user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Defense-in-depth: ensure user is admin (route middleware should already enforce this)
        if (!auth()->user()->is_admin) {
            return redirect()->route('dashboard')->with('warning', 'Acceso denegado.');
        }

        // If GET, show a simple confirmation form
        if ($request->isMethod('get')) {
            return view('admin.products.export');
        }

        $user = auth()->user();

        Log::info('ProductsExportController: dispatching export job', [
            'user_id' => $user->id,
            'email' => $user->email,
            'remote' => request()->ip(),
        ]);

        // In local environment run synchronously for immediate feedback during testing
        if (app()->environment('local')) {
            Bus::dispatchSync(new ProductsExportJob($user->email));
            return redirect()->back()->with('success', 'Exportación procesada (sync en local). Revisa tu email.');
        }

        ProductsExportJob::dispatch($user->email);

        return redirect()->back()->with('success', 'Exportación encolada. Recibirás un email cuando esté lista.');
    }

    // Summary endpoint removed as part of UI simplification
}