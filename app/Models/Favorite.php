<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo: Favorite (Producto Favorito)
 * 
 * Representa un producto guardado como favorito por un usuario cliente.
 * Permite a los clientes:
 * - Guardar productos para acceso rápido
 * - Agregar notas personales a cada favorito
 * - Recibir notificaciones de cambios de precio/stock (futuro)
 */
class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'notes',
    ];

    /**
     * Relación: Usuario dueño del favorito
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Producto marcado como favorito
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope: Favoritos de un usuario específico
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Verifica si un producto específico está en favoritos del usuario
     */
    public static function isProductFavorited(int $userId, int $productId): bool
    {
        return static::where('user_id', $userId)
                     ->where('product_id', $productId)
                     ->exists();
    }

    /**
     * Toggle favorito: agrega si no existe, elimina si existe
     */
    public static function toggle(int $userId, int $productId): array
    {
        $existing = static::where('user_id', $userId)
                         ->where('product_id', $productId)
                         ->first();

        if ($existing) {
            $existing->delete();
            return ['action' => 'removed', 'favorited' => false];
        }

        static::create([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);

        return ['action' => 'added', 'favorited' => true];
    }
}
