<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     * 
     * Verifica que el usuario sea administrador basándose en el campo is_admin (rol),
     * no en el correo electrónico. Esto permite que el admin cambie su correo
     * sin perder acceso al sistema.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Verificar rol de administrador
        if ($user->is_admin === true) {
            return $next($request);
        }

        abort(403, 'No autorizado.');
    }
}
