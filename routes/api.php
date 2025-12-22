<?php

use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\ProductApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Serve API docs (OpenAPI YAML)
Route::get('/docs', function () {
    $path = base_path('docs/openapi.yaml');
    if (!file_exists($path)) {
        return response()->json(['error' => 'Docs not found'], 404);
    }
    return response()->file($path, ['Content-Type' => 'application/yaml']);
});

