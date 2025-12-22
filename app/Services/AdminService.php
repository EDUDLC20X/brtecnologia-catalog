<?php

namespace App\Services;

use App\Models\User;

/**
 * Servicio centralizado para obtener información del administrador.
 * 
 * Este servicio proporciona un único punto de acceso para obtener
 * el correo del administrador, evitando duplicación de lógica.
 */
class AdminService
{
    /**
     * Obtiene el correo electrónico del administrador principal.
     * 
     * Prioridad:
     * 1. Usuario con is_admin = true (el primero encontrado)
     * 2. Fallback al correo configurado en ADMIN_EMAILS
     * 3. Fallback al MAIL_FROM_ADDRESS
     */
    public static function getAdminEmail(): string
    {
        // Buscar el primer usuario administrador activo
        $admin = User::where('is_admin', true)->first();
        
        if ($admin) {
            return $admin->email;
        }
        
        // Fallback a configuración de .env
        return config('mail.admin_address', config('mail.from.address', 'admin@example.com'));
    }

    /**
     * Obtiene el usuario administrador principal.
     */
    public static function getAdmin(): ?User
    {
        return User::where('is_admin', true)->first();
    }

    /**
     * Obtiene todos los correos de administradores.
     * 
     * @return array<string>
     */
    public static function getAllAdminEmails(): array
    {
        $emails = User::where('is_admin', true)->pluck('email')->toArray();
        
        if (empty($emails)) {
            $fallback = config('mail.admin_address', config('mail.from.address'));
            return $fallback ? [$fallback] : [];
        }
        
        return $emails;
    }

    /**
     * Verifica si un usuario es administrador basándose en su rol.
     */
    public static function isAdmin(User $user): bool
    {
        return $user->is_admin === true;
    }
}
