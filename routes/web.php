<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductCatalogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Catálogo B&R Tecnología
|--------------------------------------------------------------------------
*/

// ============================================
// RUTAS PÚBLICAS
// ============================================

Route::get('/', function () {
    return view('home');
})->name('home');

// Catálogo de productos
Route::get('/catalog', [ProductCatalogController::class, 'index'])->name('catalog.index');
Route::get('/productos', [ProductCatalogController::class, 'index'])->name('catalog');
Route::get('/productos/{product}', [ProductCatalogController::class, 'show'])->name('catalog.show');

// Páginas informativas
Route::get('/about', function () { return view('about'); })->name('about');
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact/send', [ContactController::class, 'send'])->middleware('throttle:contact')->name('contact.send');

// API docs
Route::get('/api/docs/ui', function () { return view('api.docs'); })->name('api.docs.ui');

// Ruta para crear productos (API/tests)
Route::post('/products', [ProductController::class, 'store'])->name('products.store');

// ============================================
// RUTAS AUTENTICADAS
// ============================================

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/email-change', [ProfileController::class, 'requestEmailChange'])->name('profile.request-email-change');
    Route::post('/profile/email-change/cancel', [ProfileController::class, 'cancelEmailChange'])->name('profile.cancel-email-change');
});

Route::get('/profile/verify-email/{token}', [ProfileController::class, 'verifyEmailChange'])->name('profile.verify-email-change');

// ============================================
// RUTAS DE ADMINISTRACIÓN
// ============================================

Route::middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Categorías
    Route::get('/admin/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::get('/admin/categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');
    Route::post('/admin/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('/admin/categories/{category}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::put('/admin/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/admin/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
    
    // CMS
    Route::get('/admin/content', [ContentController::class, 'index'])->name('admin.content.index');
    Route::get('/admin/content/{section}', [ContentController::class, 'editSection'])->name('admin.content.section');
    Route::put('/admin/content/{section}', [ContentController::class, 'updateSection'])->name('admin.content.section.update');
    Route::post('/admin/content/reset/{id}', [ContentController::class, 'resetContent'])->name('admin.content.reset');
    Route::delete('/admin/content/image/{id}', [ContentController::class, 'removeImage'])->name('admin.content.image.remove');
    Route::get('/admin/content/{section}/preview', [ContentController::class, 'preview'])->name('admin.content.preview');
    
    // Productos
    Route::get('/products/exports', function () { return redirect('/products/export'); });
    Route::match(['get', 'post'], '/products/export', [ProductController::class, 'export'])->name('products.export');
    Route::resource('products', ProductController::class)->except(['store']);
});

require __DIR__.'/auth.php';
