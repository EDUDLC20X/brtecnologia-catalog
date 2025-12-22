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

        Log::info('=== PASSWORD RESET: Iniciando envío ===', [
            'email' => $request->email,
            'mail_config' => \App\Services\MailService::getMailConfig(),
        ]);

        try {
            // Enviar enlace de recuperación
            $status = Password::sendResetLink(
                $request->only('email')
            );

            Log::info('=== PASSWORD RESET: Resultado ===', [
                'status' => $status,
                'status_name' => $status == Password::RESET_LINK_SENT ? 'SENT' : 'FAILED',
            ]);

            return $status == Password::RESET_LINK_SENT
                        ? back()->with('status', 'Si el correo existe, recibirás un enlace de recuperación.')
                        : back()->with('status', 'Si el correo existe, recibirás un enlace de recuperación.');
        } catch (\Exception $e) {
            Log::error('=== PASSWORD RESET: Error ===', [
                'email' => $request->email,
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            // No revelar si el correo existe o no por seguridad
            return back()->with('status', 'Si el correo existe, recibirás un enlace de recuperación.');
        }
    }
}
