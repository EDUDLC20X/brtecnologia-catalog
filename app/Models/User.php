<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        'is_admin' => 'boolean',
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