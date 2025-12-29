<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Quote;
use App\Models\ProductRequest;
use App\Models\SiteContent;

class VerifySystem extends Command
{
    protected $signature = 'system:verify {--full : Ejecutar verificación completa incluyendo emails}';
    protected $description = 'Verifica todas las funcionalidades del sistema B&R Tecnología';

    private $passed = 0;
    private $failed = 0;
    private $warnings = 0;

    public function handle()
    {
        $this->info('');
        $this->info('╔══════════════════════════════════════════════════════════════╗');
        $this->info('║     🔍 VERIFICACIÓN DEL SISTEMA - B&R Tecnología            ║');
        $this->info('║                    v1.0 - Laravel 9                          ║');
        $this->info('╚══════════════════════════════════════════════════════════════╝');
        $this->info('');

        // 1. Base de Datos
        $this->verifyDatabase();
        
        // 2. Modelos y Datos
        $this->verifyModels();
        
        // 3. Rutas
        $this->verifyRoutes();
        
        // 4. Almacenamiento
        $this->verifyStorage();
        
        // 5. Configuración
        $this->verifyConfiguration();
        
        // 6. Autenticación
        $this->verifyAuth();
        
        // 7. CMS
        $this->verifyCMS();
        
        // 8. Email (opcional)
        if ($this->option('full')) {
            $this->verifyEmail();
        }

        // Resumen Final
        $this->showSummary();

        return $this->failed > 0 ? 1 : 0;
    }

    private function verifyDatabase()
    {
        $this->section('BASE DE DATOS');

        try {
            DB::connection()->getPdo();
            $this->pass('Conexión a base de datos');
            
            $dbName = DB::connection()->getDatabaseName();
            $this->info("   └─ Base de datos: {$dbName}");
            
            // Verificar tablas principales
            $tables = ['users', 'products', 'categories', 'quotes', 'quote_items', 'product_requests', 'site_contents'];
            foreach ($tables as $table) {
                if (DB::getSchemaBuilder()->hasTable($table)) {
                    $count = DB::table($table)->count();
                    $this->pass("Tabla '{$table}' ({$count} registros)");
                } else {
                    $this->fail("Tabla '{$table}' no existe");
                }
            }
        } catch (\Exception $e) {
            $this->fail('Conexión a base de datos: ' . $e->getMessage());
        }
    }

    private function verifyModels()
    {
        $this->section('MODELOS Y DATOS');

        // Productos
        $products = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        if ($products > 0) {
            $this->pass("Productos: {$products} total, {$activeProducts} activos");
        } else {
            $this->warning('No hay productos en la base de datos');
        }

        // Categorías
        $categories = Category::count();
        if ($categories > 0) {
            $this->pass("Categorías: {$categories}");
            
            // Verificar productos por categoría
            $catWithProducts = Category::withCount('products')->get();
            foreach ($catWithProducts as $cat) {
                $this->info("   └─ {$cat->name}: {$cat->products_count} productos");
            }
        } else {
            $this->warning('No hay categorías');
        }

        // Usuarios
        $users = User::count();
        $admins = User::where('is_admin', true)->count();
        $clients = User::where('is_admin', false)->count();
        if ($users > 0) {
            $this->pass("Usuarios: {$users} total ({$admins} admin, {$clients} clientes)");
        } else {
            $this->warning('No hay usuarios registrados');
        }

        // Cotizaciones
        $quotes = Quote::count();
        $this->info("   └─ Cotizaciones: {$quotes}");

        // Solicitudes
        $requests = ProductRequest::count();
        $pending = ProductRequest::where('status', 'pending')->count();
        $this->info("   └─ Solicitudes: {$requests} total, {$pending} pendientes");
    }

    private function verifyRoutes()
    {
        $this->section('RUTAS PRINCIPALES');

        $criticalRoutes = [
            'home' => 'Página principal',
            'catalog.index' => 'Catálogo',
            'login' => 'Login',
            'register' => 'Registro',
            'contact' => 'Contacto',
            'quote.index' => 'Cotización',
            'about' => 'Acerca de',
        ];

        foreach ($criticalRoutes as $routeName => $description) {
            if (Route::has($routeName)) {
                $this->pass("{$description} ({$routeName})");
            } else {
                $this->fail("{$description} - Ruta '{$routeName}' no existe");
            }
        }

        // Rutas de Admin
        $adminRoutes = ['admin.dashboard', 'products.index', 'admin.content.index'];
        $adminOk = 0;
        foreach ($adminRoutes as $route) {
            if (Route::has($route)) $adminOk++;
        }
        if ($adminOk === count($adminRoutes)) {
            $this->pass('Rutas de administración (' . count($adminRoutes) . ')');
        } else {
            $this->warning("Rutas de admin: {$adminOk}/" . count($adminRoutes) . " disponibles");
        }

        // Rutas de Cliente
        $clientRoutes = ['client.dashboard', 'client.favorites.index', 'client.requests.index'];
        $clientOk = 0;
        foreach ($clientRoutes as $route) {
            if (Route::has($route)) $clientOk++;
        }
        if ($clientOk === count($clientRoutes)) {
            $this->pass('Rutas de cliente (' . count($clientRoutes) . ')');
        } else {
            $this->warning("Rutas de cliente: {$clientOk}/" . count($clientRoutes) . " disponibles");
        }
    }

    private function verifyStorage()
    {
        $this->section('ALMACENAMIENTO');

        // Verificar directorios
        $directories = [
            'app/public' => storage_path('app/public'),
            'logs' => storage_path('logs'),
            'cache' => storage_path('framework/cache'),
            'sessions' => storage_path('framework/sessions'),
            'views' => storage_path('framework/views'),
        ];

        foreach ($directories as $name => $path) {
            if (is_dir($path) && is_writable($path)) {
                $this->pass("Directorio '{$name}' escribible");
            } else {
                $this->fail("Directorio '{$name}' no existe o no es escribible");
            }
        }

        // Verificar symlink de storage
        $publicStorage = public_path('storage');
        if (is_link($publicStorage) || is_dir($publicStorage)) {
            $this->pass('Symlink public/storage');
        } else {
            $this->warning('Symlink public/storage no existe (ejecutar: php artisan storage:link)');
        }

        // Verificar imágenes base
        $requiredImages = ['logo-br.png', 'logo-white.png'];
        foreach ($requiredImages as $image) {
            if (file_exists(public_path("images/{$image}"))) {
                $this->pass("Imagen '{$image}'");
            } else {
                $this->warning("Imagen '{$image}' no encontrada");
            }
        }
    }

    private function verifyConfiguration()
    {
        $this->section('CONFIGURACIÓN');

        // APP_KEY
        if (config('app.key')) {
            $this->pass('APP_KEY configurada');
        } else {
            $this->fail('APP_KEY no configurada');
        }

        // APP_DEBUG
        if (config('app.debug')) {
            $this->warning('APP_DEBUG está activado (desactivar en producción)');
        } else {
            $this->pass('APP_DEBUG desactivado');
        }

        // APP_ENV
        $env = config('app.env');
        $this->info("   └─ Entorno: {$env}");

        // Mail
        $mailDriver = config('mail.default');
        if ($mailDriver && $mailDriver !== 'log') {
            $this->pass("Driver de correo: {$mailDriver}");
        } else {
            $this->warning("Driver de correo: {$mailDriver} (configurar para producción)");
        }

        // Session
        $sessionDriver = config('session.driver');
        $this->info("   └─ Driver de sesión: {$sessionDriver}");

        // Cache
        $cacheDriver = config('cache.default');
        $this->info("   └─ Driver de caché: {$cacheDriver}");
    }

    private function verifyAuth()
    {
        $this->section('AUTENTICACIÓN');

        // Verificar que exista un admin
        $admin = User::where('is_admin', true)->first();
        if ($admin) {
            $this->pass("Usuario admin existe: {$admin->email}");
        } else {
            $this->fail('No existe usuario administrador');
        }

        // Verificar middlewares
        $middlewares = ['auth', 'admin', 'client', 'guest'];
        $kernel = app(\Illuminate\Contracts\Http\Kernel::class);
        
        foreach ($middlewares as $middleware) {
            if (app('router')->getMiddleware()[$middleware] ?? false) {
                $this->pass("Middleware '{$middleware}'");
            } else {
                $this->warning("Middleware '{$middleware}' no registrado");
            }
        }
    }

    private function verifyCMS()
    {
        $this->section('CMS / CONTENIDO');

        $totalContent = SiteContent::count();
        if ($totalContent > 0) {
            $this->pass("Contenido CMS: {$totalContent} entradas");
            
            // Verificar secciones principales
            $sections = ['global', 'home', 'contact'];
            foreach ($sections as $section) {
                $count = SiteContent::where('key', 'like', "{$section}.%")->count();
                $this->info("   └─ Sección '{$section}': {$count} campos");
            }
        } else {
            $this->warning('No hay contenido CMS (ejecutar: php artisan db:seed --class=SiteContentSeeder)');
        }
    }

    private function verifyEmail()
    {
        $this->section('EMAIL (PRUEBA)');

        try {
            $adminEmail = config('mail.from.address');
            if ($adminEmail) {
                $this->info("   └─ Email configurado: {$adminEmail}");
                $this->pass('Configuración de email válida');
            } else {
                $this->warning('Email de envío no configurado');
            }
        } catch (\Exception $e) {
            $this->fail('Error en configuración de email: ' . $e->getMessage());
        }
    }

    private function showSummary()
    {
        $this->info('');
        $this->info('╔══════════════════════════════════════════════════════════════╗');
        $this->info('║                    📊 RESUMEN FINAL                          ║');
        $this->info('╚══════════════════════════════════════════════════════════════╝');
        $this->info('');
        
        $total = $this->passed + $this->failed + $this->warnings;
        
        $this->info("   ✅ Verificaciones exitosas: {$this->passed}");
        
        if ($this->warnings > 0) {
            $this->warning("   ⚠️  Advertencias: {$this->warnings}");
        }
        
        if ($this->failed > 0) {
            $this->error("   ❌ Errores: {$this->failed}");
        }
        
        $this->info('');
        
        if ($this->failed === 0) {
            $this->info('   🎉 ¡Sistema verificado correctamente!');
            if ($this->warnings > 0) {
                $this->info('   💡 Revisa las advertencias antes de ir a producción.');
            }
        } else {
            $this->error('   ⚠️  Hay errores que deben corregirse.');
        }
        
        $this->info('');
    }

    // Helpers
    private function section($title)
    {
        $this->info('');
        $this->info("┌─ {$title} " . str_repeat('─', 50 - strlen($title)));
    }

    private function pass($message)
    {
        $this->passed++;
        $this->line("   <fg=green>✓</> {$message}");
    }

    private function fail($message)
    {
        $this->failed++;
        $this->line("   <fg=red>✗</> {$message}");
    }

    private function warning($message)
    {
        $this->warnings++;
        $this->line("   <fg=yellow>⚠</> {$message}");
    }
}
