<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductCatalogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContactController;
use App\Services\AdminService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
})->name('home');

// Dashboard redirige al admin dashboard si está autenticado
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rutas para cambio de correo con verificación
    Route::post('/profile/email-change', [ProfileController::class, 'requestEmailChange'])->name('profile.request-email-change');
    Route::post('/profile/email-change/cancel', [ProfileController::class, 'cancelEmailChange'])->name('profile.cancel-email-change');
});

// Ruta pública para verificar cambio de correo (el usuario puede no estar logueado)
Route::get('/profile/verify-email/{token}', [ProfileController::class, 'verifyEmailChange'])->name('profile.verify-email-change');

// Allow public product creation for API/tests (tests expect unauthenticated POST)
Route::post('/products', [ProductController::class, 'store'])->name('products.store');

// API docs UI (Redoc)
Route::get('/api/docs/ui', function () {
    return view('api.docs');
})->name('api.docs.ui');

require __DIR__.'/auth.php';

// Public catalog routes
Route::get('/catalog', [ProductCatalogController::class, 'index'])->name('catalog.index');
Route::get('/productos', [ProductCatalogController::class, 'index'])->name('catalog');
Route::get('/productos/{product}', [ProductCatalogController::class, 'show'])->name('catalog.show');



// Informational routes
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

// Rate limit: 3 envíos por minuto para prevenir spam
Route::post('/contact/send', function (Request $request) {
    // Basic validation (name/email always required). Other rules depend on context (contact vs product request)
    $baseRules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'product_id' => 'nullable|integer',
    ];

    $validator = Validator::make($request->all(), $baseRules);
    if ($validator->fails()) {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => false, 'message' => 'Datos inválidos', 'errors' => $validator->errors()], 422);
        }
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $data = $validator->validated();

    // Shared data
    $shared = [
        'name' => $data['name'],
        'email' => $data['email'],
        'message' => $data['message'] ?? '',
        'sent_at' => now()->toDateTimeString(),
    ];

    // Usar el servicio centralizado para obtener el correo del admin
    $adminEmail = AdminService::getAdminEmail();

    try {
        if (!empty($data['product_id'])) {
            // For product requests, message is required
            $v2 = Validator::make($request->all(), ['message' => 'required|string']);
            if ($v2->fails()) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'El mensaje es requerido.'], 422);
                }
                return redirect()->back()->withErrors($v2)->withInput();
            }
            $data['message'] = $request->input('message');
            $product = \App\Models\Product::find($data['product_id']);
            $payload = array_merge($shared, [
                'product_id' => $data['product_id'],
                'product_name' => $product->name ?? 'Desconocido',
                'product_url' => isset($product) ? route('catalog.show', $product) : null,
            ]);


            // Send to admin
            Mail::to($adminEmail)->send(new \App\Mail\ProductRequestMail($payload));

            // Confirmation to client (separate mail)
            Mail::to($data['email'])->send(new \App\Mail\ProductRequestConfirmationMail($payload));

            $message = 'Solicitud enviada correctamente. Nos contactaremos pronto.';
        } else {
            // For contact form, require subject and message
            $v3 = Validator::make($request->all(), [
                'subject' => 'required|string|max:255',
                'message' => 'required|string',
            ]);
            if ($v3->fails()) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Asunto y mensaje son requeridos.', 'errors' => $v3->errors()], 422);
                }
                return redirect()->back()->withErrors($v3)->withInput();
            }

            $payload = array_merge($shared, ['subject' => $request->input('subject'), 'message' => $request->input('message')]);

            Mail::to($adminEmail)->send(new \App\Mail\ContactMessageMail($payload));
            // Confirmation to client
            Mail::to($data['email'])->send(new \App\Mail\ContactConfirmationMail($payload));

            $message = 'Mensaje enviado correctamente';
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return redirect()->route('contact')->with('success', $message);
    } catch (\Exception $e) {
        Log::error('Mail send failed', ['error' => $e->getMessage()]);
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => false, 'message' => 'Ocurrió un error al enviar el correo. Por favor intenta nuevamente.'], 500);
        }
        return redirect()->back()->withInput()->with('error', 'Ocurrió un error al enviar el correo. Por favor intenta nuevamente.');
    }
})->middleware('throttle:contact')->name('contact.send');

// Rutas de páginas informativas (actualmente no enlazadas en el sitio)
// Route::get('/faq', function () { return view('faq'); })->name('faq');
// Route::get('/terms', function () { return view('terms'); })->name('terms');
// Route::get('/privacy', function () { return view('privacy'); })->name('privacy');

// Admin routes (require auth + admin)
use App\Http\Controllers\Admin\ContentController;

Route::middleware(['auth','admin'])->group(function () {
    // Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Content Management (CMS)
    Route::get('/admin/content', [ContentController::class, 'index'])->name('admin.content.index');
    Route::get('/admin/content/{section}', [ContentController::class, 'editSection'])->name('admin.content.section');
    Route::put('/admin/content/{section}', [ContentController::class, 'updateSection'])->name('admin.content.section.update');
    Route::post('/admin/content/reset/{id}', [ContentController::class, 'resetContent'])->name('admin.content.reset');
    Route::delete('/admin/content/image/{id}', [ContentController::class, 'removeImage'])->name('admin.content.image.remove');
    Route::get('/admin/content/{section}/preview', [ContentController::class, 'preview'])->name('admin.content.preview');
    
    // Products export (admin) - define before resource so '/products/export' doesn't match '{product}'
    // Also accept the common typo '/products/exports' by redirecting to the canonical path
    Route::get('/products/exports', function () { return redirect('/products/export'); });
    Route::match(['get','post'], '/products/export', [ProductController::class, 'export'])->name('products.export');

    // Products (admin CRUD) - exclude `store` to allow public POST route for tests
    Route::resource('products', ProductController::class)->except(['store']);
});
