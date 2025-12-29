<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

/**
 * Modelo: ProductView (Vista de Producto)
 * 
 * Registra el historial de productos visualizados por cada usuario.
 * Útil para:
 * - Mostrar "Productos vistos recientemente"
 * - Generar recomendaciones personalizadas
 * - Análisis de comportamiento de navegación
 */
class ProductView extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'view_count',
        'last_viewed_at',
    ];

    protected $casts = [
        'last_viewed_at' => 'datetime',
        'view_count' => 'integer',
    ];

    /**
     * Relación: Usuario que visualizó el producto
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Producto visualizado
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Registra o actualiza una vista de producto para un usuario
     */
    public static function recordView(int $userId, int $productId): self
    {
        return static::updateOrCreate(
            [
                'user_id' => $userId,
                'product_id' => $productId,
            ],
            [
                'view_count' => DB::raw('view_count + 1'),
                'last_viewed_at' => now(),
            ]
        );
    }

    /**
     * Obtiene el historial de productos vistos por un usuario
     */
    public static function getHistory(int $userId, int $limit = 20)
    {
        return static::with(['product' => function($query) {
                $query->with(['mainImage', 'category'])
                      ->where('is_active', true);
            }])
            ->where('user_id', $userId)
            ->whereHas('product', function($query) {
                $query->where('is_active', true);
            })
            ->orderByDesc('last_viewed_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtiene productos recomendados basados en el historial
     * (productos de las mismas categorías que el usuario ha visto)
     */
    public static function getRecommendations(int $userId, int $limit = 8)
    {
        // Obtener categorías de productos vistos recientemente
        $categoryIds = static::where('user_id', $userId)
            ->with('product')
            ->orderByDesc('last_viewed_at')
            ->limit(10)
            ->get()
            ->pluck('product.category_id')
            ->unique()
            ->filter()
            ->values();

        if ($categoryIds->isEmpty()) {
            // Si no hay historial, retornar productos destacados
            return Product::with(['mainImage', 'category'])
                ->where('is_active', true)
                ->where('is_featured', true)
                ->limit($limit)
                ->get();
        }

        // Obtener IDs de productos ya vistos para excluirlos
        $viewedProductIds = static::where('user_id', $userId)
            ->pluck('product_id');

        return Product::with(['mainImage', 'category'])
            ->where('is_active', true)
            ->whereIn('category_id', $categoryIds)
            ->whereNotIn('id', $viewedProductIds)
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }
}
