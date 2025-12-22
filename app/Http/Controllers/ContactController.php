<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        // Validar datos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10|max:5000',
        ], [
            'name.required' => 'El nombre es requerido',
            'name.string' => 'El nombre debe ser texto',
            'email.required' => 'El correo es requerido',
            'email.email' => 'Ingresa un correo válido',
            'subject.required' => 'El asunto es requerido',
            'message.required' => 'El mensaje es requerido',
            'message.min' => 'El mensaje debe tener al menos 10 caracteres',
        ]);

        // Aquí puedes:
        // 1. Guardar en BD
        // 2. Enviar email
        // 3. Notificar al admin
        
        // Por ahora, solo redireccionamos con mensaje de éxito
        return redirect()->route('contact')
            ->with('success', '¡Mensaje enviado con éxito! Nos pondremos en contacto pronto.');
    }
}
