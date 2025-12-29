<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: EnsureUserIsClient
 * 
 * Verifica que el usuario autenticado sea un cliente (no administrador).
 * Se utiliza para proteger rutas exclusivas de clientes.
 */
class EnsureUserIsClient
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para acceder a esta sección.');
        }

        // Verificar que NO sea administrador
        if ($user->is_admin === true) {
            // Los admins no necesitan el panel de cliente, redirigir a admin
            return redirect()->route('admin.dashboard')
                ->with('info', 'Como administrador, tienes acceso a funciones avanzadas en el panel de administración.');
        }

        return $next($request);
    }
}
