<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Modelo: User
 * 
 * Representa a los usuarios del sistema con dos roles principales:
 * 
 * 1. ADMINISTRADOR (is_admin = true):
 *    - Acceso total al sistema
 *    - Gestión completa del catálogo (productos, categorías)
 *    - Gestión de imágenes e iconos
 *    - Gestión de usuarios y contenido
 * 
 * 2. CLIENTE (is_admin = false):
 *    - Inicio de sesión opcional pero funcional
 *    - Guardar productos como favoritos
 *    - Historial de productos visualizados
 *    - Realizar solicitudes de productos
 *    - Personalización básica de experiencia
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'phone',
        'avatar',
        'notify_offers',
        'notify_new_products',
        'last_activity_at',
        'pending_email',
        'email_change_token',
        'email_change_requested_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_change_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'email_change_requested_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'is_admin' => 'boolean',
        'notify_offers' => 'boolean',
        'notify_new_products' => 'boolean',
    ];

    /**
     * Determine if the user is considered an admin for the app.
     * 
     * Ahora basado en el campo is_admin (rol), no en el correo electrónico.
     */
    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }

    /**
     * Determine if the user is a client (non-admin user).
     */
    public function isClient(): bool
    {
        return $this->is_admin === false;
    }

    /**
     * Relación: Productos favoritos del usuario
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Relación: Productos favoritos (acceso directo)
     */
    public function favoriteProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'favorites')
                    ->withTimestamps()
                    ->withPivot('notes');
    }

    /**
     * Relación: Historial de productos visualizados
     */

    /**
     * Relación: Solicitudes de productos
     */
    public function productRequests(): HasMany
    {
        return $this->hasMany(ProductRequest::class);
    }

    /**
     * Verifica si un producto está en favoritos del usuario
     */
    public function hasFavorited(int $productId): bool
    {
        return $this->favorites()->where('product_id', $productId)->exists();
    }

    /**
     * Obtiene el conteo de favoritos del usuario
     */
    public function getFavoritesCountAttribute(): int
    {
        return $this->favorites()->count();
    }

    /**
     * Registra actividad del usuario
     */
    public function recordActivity(): void
    {
        $this->update(['last_activity_at' => now()]);
    }

    /**
     * Verifica si hay un cambio de correo pendiente.
     */
    public function hasPendingEmailChange(): bool
    {
        return !empty($this->pending_email) && !empty($this->email_change_token);
    }

    /**
     * Verifica si el token de cambio de correo ha expirado.
     */
    public function emailChangeTokenExpired(): bool
    {
        if (!$this->email_change_requested_at) {
            return true;
        }

        return $this->email_change_requested_at->diffInMinutes(now()) > 60;
    }
}