<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        try {
            // Enviar enlace de recuperación
            $status = Password::sendResetLink(
                $request->only('email')
            );

            return $status == Password::RESET_LINK_SENT
                        ? back()->with('status', 'Si el correo existe, recibirás un enlace de recuperación.')
                        : back()->with('status', 'Si el correo existe, recibirás un enlace de recuperación.');
        } catch (\Exception $e) {
            Log::error('Error enviando correo de recuperación: ' . $e->getMessage());
            
            // No revelar si el correo existe o no por seguridad
            return back()->with('status', 'Si el correo existe, recibirás un enlace de recuperación.');
        }
    }
}
