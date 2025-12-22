<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
        
        // Manejar error 405 redirigiendo a home o página apropiada
        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Método no permitido'], 405);
            }
            
            // Si es una ruta de profile, redirigir a profile
            if (str_contains($request->path(), 'profile')) {
                return redirect()->route('profile.edit')
                    ->with('error', 'Acción no válida. Por favor usa el formulario.');
            }
            
            // Para otras rutas, redirigir al home
            return redirect()->route('home');
        });
    }
}