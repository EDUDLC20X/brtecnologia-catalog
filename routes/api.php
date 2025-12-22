<?php

use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\ProductApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Catálogo B&R Tecnología
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    // Productos
    Route::get('/products', [ProductApiController::class, 'index']);
    Route::get('/products/search', [ProductApiController::class, 'search']);
    Route::get('/products/{id}', [ProductApiController::class, 'show']);

    // Categorías
    Route::get('/categories', [CategoryApiController::class, 'index']);
    Route::get('/categories/{category}/products', [CategoryApiController::class, 'products']);
});

