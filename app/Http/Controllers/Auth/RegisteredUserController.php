<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

/**
 * RegisteredUserController
 * 
 * Gestiona el registro de nuevos usuarios cliente.
 * 
 * Nota: El registro solo está disponible para CLIENTES.
 * Los administradores se crean mediante seeder o manualmente en la base de datos.
 */
class RegisteredUserController extends Controller
{
    /**
     * Muestra la vista de registro
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Procesa el registro de un nuevo usuario cliente
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['accepted'],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'terms.accepted' => 'Debes aceptar los términos y condiciones.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'is_admin' => false, // Siempre cliente
            'notify_offers' => true,
            'notify_new_products' => true,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('client.dashboard')
            ->with('success', '¡Bienvenido a B&R Tecnología! Tu cuenta ha sido creada exitosamente.');
    }
}
