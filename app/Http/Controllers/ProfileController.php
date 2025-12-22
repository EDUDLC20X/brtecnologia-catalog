<?php

namespace App\Http\Controllers;

use App\Mail\EmailChangeVerification;
use App\Services\MailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information (solo nombre).
     * El correo se actualiza mediante verificación.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ], [
            'name.required' => 'El nombre es requerido.',
        ]);

        $request->user()->update([
            'name' => $request->name,
        ]);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Solicitar cambio de correo electrónico.
     * Envía un correo de verificación al nuevo email.
     */
    public function requestEmailChange(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
        ], [
            'email.required' => 'El correo electrónico es requerido.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'email.unique' => 'Este correo electrónico ya está en uso.',
        ]);

        $user = $request->user();
        $newEmail = $request->email;

        // Verificar que el nuevo correo sea diferente
        if ($user->email === $newEmail) {
            return Redirect::route('profile.edit')
                ->withErrors(['email' => 'El nuevo correo debe ser diferente al actual.']);
        }

        // Generar token único
        $token = Str::random(64);

        // Guardar datos del cambio pendiente
        $user->update([
            'pending_email' => $newEmail,
            'email_change_token' => hash('sha256', $token),
            'email_change_requested_at' => now(),
        ]);

        // Generar URL de verificación
        $verificationUrl = route('profile.verify-email-change', ['token' => $token]);

        // Enviar correo de verificación al nuevo email usando MailService
        $result = MailService::send(
            $newEmail,
            new EmailChangeVerification($user->name, $newEmail, $verificationUrl),
            'email-change-verification'
        );
        
        if (!$result['success']) {
            // Si falla el envío, limpiar datos pendientes
            $user->update([
                'pending_email' => null,
                'email_change_token' => null,
                'email_change_requested_at' => null,
            ]);
            
            Log::error('Error enviando correo de verificación de cambio de email', $result);
            
            return Redirect::route('profile.edit')
                ->withErrors(['email' => 'No se pudo enviar el correo de verificación. Intenta más tarde.']);
        }

        return Redirect::route('profile.edit')
            ->with('status', 'email-verification-sent')
            ->with('pending_email', $newEmail);
    }

    /**
     * Verificar y aplicar el cambio de correo electrónico.
     */
    public function verifyEmailChange(Request $request, string $token): RedirectResponse
    {
        $hashedToken = hash('sha256', $token);

        // Buscar usuario con este token
        $user = \App\Models\User::where('email_change_token', $hashedToken)
            ->whereNotNull('pending_email')
            ->first();

        if (!$user) {
            return Redirect::route('login')
                ->withErrors(['email' => 'El enlace de verificación es inválido.']);
        }

        // Verificar que no haya expirado (60 minutos)
        if ($user->email_change_requested_at->diffInMinutes(now()) > 60) {
            // Limpiar datos pendientes
            $user->update([
                'pending_email' => null,
                'email_change_token' => null,
                'email_change_requested_at' => null,
            ]);

            return Redirect::route('profile.edit')
                ->withErrors(['email' => 'El enlace de verificación ha expirado. Solicita un nuevo cambio.']);
        }

        // Aplicar el cambio de correo
        $oldEmail = $user->email;
        $newEmail = $user->pending_email;

        $user->update([
            'email' => $newEmail,
            'email_verified_at' => now(),
            'pending_email' => null,
            'email_change_token' => null,
            'email_change_requested_at' => null,
        ]);

        // Si el usuario está logueado, mantener la sesión
        if (Auth::check() && Auth::id() === $user->id) {
            return Redirect::route('profile.edit')
                ->with('status', 'email-changed')
                ->with('old_email', $oldEmail);
        }

        // Si no está logueado, redirigir al login
        return Redirect::route('login')
            ->with('status', 'Correo electrónico actualizado correctamente. Inicia sesión con tu nuevo correo.');
    }

    /**
     * Cancelar el cambio de correo pendiente.
     */
    public function cancelEmailChange(Request $request): RedirectResponse
    {
        $request->user()->update([
            'pending_email' => null,
            'email_change_token' => null,
            'email_change_requested_at' => null,
        ]);

        return Redirect::route('profile.edit')
            ->with('status', 'email-change-cancelled');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ], [
            'password.required' => 'La contraseña es requerida.',
            'password.current_password' => 'La contraseña es incorrecta.',
        ]);

        $user = $request->user();

        // No permitir eliminar si es el único admin
        if ($user->is_admin) {
            $adminCount = \App\Models\User::where('is_admin', true)->count();
            if ($adminCount <= 1) {
                return Redirect::route('profile.edit')
                    ->withErrors(['delete' => 'No puedes eliminar la única cuenta de administrador del sistema.']);
            }
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
