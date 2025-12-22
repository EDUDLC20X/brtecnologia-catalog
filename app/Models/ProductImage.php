<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'path', 'cloudinary_public_id', 'is_main'];

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
        
        // If it's a full URL (Cloudinary), return as-is
        if (str_starts_with($this->path, 'http://') || str_starts_with($this->path, 'https://')) {
            return $this->path;
        }
        
        // La ruta en BD es: products/xxxxx/xxxxx.png
        // Necesitamos: /storage/products/xxxxx/xxxxx.png
        return '/storage/' . $this->path;
    }
}