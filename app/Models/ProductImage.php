<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = ['product_id','path','is_main'];

    protected $casts = [
        'is_main' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // accesor para obtener URL correcta
    public function getUrlAttribute()
    {
        if (!$this->path) {
            return null;
        }
        // La ruta en BD es: products/xxxxx/xxxxx.png
        // Necesitamos: /storage/products/xxxxx/xxxxx.png
        return '/storage/' . $this->path;
    }
}