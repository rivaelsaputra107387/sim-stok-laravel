<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockAlert extends Model
{
    use HasFactory;

    public const TYPES = [
        'MINIMUM_STOCK' => 'minimum_stock',
        'OUT_OF_STOCK' => 'out_of_stock',
        'EXPIRED' => 'expired',
        'NEAR_EXPIRY' => 'near_expiry',
    ];

    protected $fillable = [
        'product_id',
        'type',
        'message',
        'is_read',
        'alert_date',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'alert_date' => 'datetime',
    ];

    // Relationships
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeMinimumStock($query)
    {
        return $query->where('type', self::TYPES['MINIMUM_STOCK']);
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('type', self::TYPES['OUT_OF_STOCK']);
    }

    public function scopeExpired($query)
    {
        return $query->where('type', self::TYPES['EXPIRED']);
    }

    public function scopeNearExpiry($query)
    {
        return $query->where('type', self::TYPES['NEAR_EXPIRY']);
    }
}
