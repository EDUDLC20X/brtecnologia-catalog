<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name','slug','icon'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    protected static function booted()
    {
        static::saved(function (Category $category) {
            Cache::forget('catalog_categories');
            Cache::forget('catalog_categories_with_products');
        });

        static::deleting(function (Category $category) {
            Cache::forget('catalog_categories');
            Cache::forget('catalog_categories_with_products');
        });
    }
}