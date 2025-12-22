<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'sku_code',
        'name',
        'slug',
        'description',
        'technical_specs',
        'stock_available',
        'price_base',
        'is_active',
        'is_featured',
        'views_count',
        // Campos de ofertas
        'is_on_sale',
        'sale_price',
        'sale_starts_at',
        'sale_ends_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_on_sale' => 'boolean',
        'price_base' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'sale_starts_at' => 'datetime',
        'sale_ends_at' => 'datetime',
        'technical_specs' => 'array',
        'views_count' => 'integer',
    ];

    /**
     * Get the route key for the model (usar slug en URLs)
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    
    public function mainImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_main', true);
    }

    /**
     * Incrementar contador de vistas
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Verifica si el producto está actualmente en oferta válida.
     * Considera: is_on_sale = true, sale_price definido, y fechas válidas.
     */
    public function isCurrentlyOnSale(): bool
    {
        if (!$this->is_on_sale || !$this->sale_price) {
            return false;
        }

        $now = now();

        // Si no hay fechas definidas, la oferta está activa
        if (!$this->sale_starts_at && !$this->sale_ends_at) {
            return true;
        }

        // Verificar rango de fechas
        $startsOk = !$this->sale_starts_at || $now->gte($this->sale_starts_at);
        $endsOk = !$this->sale_ends_at || $now->lte($this->sale_ends_at);

        return $startsOk && $endsOk;
    }

    /**
     * Obtiene el precio actual (oferta si aplica, base si no).
     */
    public function getCurrentPriceAttribute(): float
    {
        if ($this->isCurrentlyOnSale()) {
            return (float) $this->sale_price;
        }
        return (float) $this->price_base;
    }

    /**
     * Calcula el porcentaje de descuento.
     */
    public function getDiscountPercentageAttribute(): int
    {
        if (!$this->isCurrentlyOnSale() || $this->price_base <= 0) {
            return 0;
        }

        $discount = (($this->price_base - $this->sale_price) / $this->price_base) * 100;
        return (int) round($discount);
    }

    /**
     * Scope para productos en oferta activa.
     */
    public function scopeOnSale($query)
    {
        return $query->where('is_on_sale', true)
            ->whereNotNull('sale_price')
            ->where(function ($q) {
                $q->whereNull('sale_starts_at')
                  ->orWhere('sale_starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('sale_ends_at')
                  ->orWhere('sale_ends_at', '>=', now());
            });
    }

    /**
     * Generar slug único
     */
    public static function generateSlug(string $name, ?int $excludeId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        $query = static::where('slug', $slug);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $count++;
            $query = static::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
        }

        return $slug;
    }

    protected static function booted()
    {
        // Generar slug automáticamente al crear
        static::creating(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = static::generateSlug($product->name);
            }
        });

        // Actualizar slug si cambia el nombre
        static::updating(function (Product $product) {
            if ($product->isDirty('name') && !$product->isDirty('slug')) {
                $product->slug = static::generateSlug($product->name, $product->id);
            }
        });

        // Invalidar caché cuando se modifica un producto
        static::saved(function (Product $product) {
            Cache::forget('catalog_categories');
            Cache::forget('catalog_price_range');
        });

        static::deleting(function (Product $product) {
            // Borrar archivos físicos de las imágenes asociadas antes de que los registros sean eliminados
            foreach ($product->images as $img) {
                if ($img->path) {
                    Storage::disk('public')->delete($img->path);
                }
            }
            // Invalidar caché
            Cache::forget('catalog_categories');
            Cache::forget('catalog_price_range');
        });
    }
}