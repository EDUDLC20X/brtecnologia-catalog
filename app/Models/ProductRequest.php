<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo: ProductRequest (Solicitud de Producto)
 * 
 * Almacena las solicitudes de información/cotización de productos.
 * Los usuarios autenticados tienen sus solicitudes asociadas a su cuenta,
 * mientras que los visitantes proporcionan sus datos manualmente.
 */
class ProductRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'name',
        'email',
        'phone',
        'company',
        'quantity',
        'message',
        'status',
        'admin_notes',
        'admin_reply',
        'replied_at',
        'responded_at',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'responded_at' => 'datetime',
        'replied_at' => 'datetime',
    ];

    /**
     * Estados posibles de una solicitud
     */
    const STATUS_PENDING = 'pending';
    const STATUS_CONTACTED = 'contacted';
    const STATUS_QUOTED = 'quoted';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Etiquetas legibles para los estados
     */
    public static function getStatusLabels(): array
    {
        return [
            self::STATUS_PENDING => 'Pendiente',
            self::STATUS_CONTACTED => 'Contactado',
            self::STATUS_QUOTED => 'Cotizado',
            self::STATUS_COMPLETED => 'Completado',
            self::STATUS_CANCELLED => 'Cancelado',
        ];
    }

    /**
     * Colores para los badges de estado
     */
    public static function getStatusColors(): array
    {
        return [
            self::STATUS_PENDING => 'warning',
            self::STATUS_CONTACTED => 'info',
            self::STATUS_QUOTED => 'primary',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_CANCELLED => 'secondary',
        ];
    }

    /**
     * Relación: Usuario que hizo la solicitud (opcional)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Producto solicitado
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Obtiene la etiqueta legible del estado
     */
    public function getStatusLabelAttribute(): string
    {
        return self::getStatusLabels()[$this->status] ?? $this->status;
    }

    /**
     * Obtiene el color del badge del estado
     */
    public function getStatusColorAttribute(): string
    {
        return self::getStatusColors()[$this->status] ?? 'secondary';
    }

    /**
     * Scope: Solicitudes pendientes
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope: Solicitudes de un usuario específico
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Marca la solicitud como contactada
     */
    public function markAsContacted(?string $notes = null): bool
    {
        return $this->update([
            'status' => self::STATUS_CONTACTED,
            'admin_notes' => $notes,
            'responded_at' => now(),
        ]);
    }
}
