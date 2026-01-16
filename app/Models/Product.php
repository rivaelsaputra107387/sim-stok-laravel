<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    public const UNITS = [
        'KRG' => 'krg',
        'DUS' => 'dus',
    ];

    protected $fillable = [
        'name',
        'code',
        'description',
        'category_id',
        'unit',
        // REMOVED: purchase_price, selling_price, expired_date
        'current_stock',
        'minimum_stock',
        
        'is_active',
    ];

    protected $casts = [
        'current_stock' => 'integer',
        'minimum_stock' => 'integer',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function stockTransactions(): HasMany
    {
        return $this->hasMany(StockTransaction::class);
    }

    public function stockAlerts(): HasMany
    {
        return $this->hasMany(StockAlert::class);
    }

    // REVISED: Accessors without price and expired date calculations
    public function getStockStatusAttribute(): string
    {
        if ($this->current_stock <= 0) {
            return 'out_of_stock';
        }

        if ($this->current_stock <= $this->minimum_stock) {
            return 'low_stock';
        }

        return 'normal';
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->current_stock <= $this->minimum_stock && $this->current_stock > 0;
    }

    public function getIsOutOfStockAttribute(): bool
    {
        return $this->current_stock <= 0;
    }

    // NEW: Get earliest expired date from stock transactions
    public function getEarliestExpiredDateAttribute(): ?string
    {
        $earliestExpired = $this->stockTransactions()
            ->where('type', 'in')
            ->whereNotNull('expired_date')
            ->where('expired_date', '>=', now())
            ->orderBy('expired_date', 'asc')
            ->first();

        return $earliestExpired ? $earliestExpired->expired_date->format('Y-m-d') : null;
    }

    // NEW: Get all expired batches
    public function getExpiredBatchesAttribute()
    {
        return $this->stockTransactions()
            ->where('type', 'in')
            ->whereNotNull('expired_date')
            ->where('expired_date', '<', now())
            ->orderBy('expired_date', 'desc')
            ->get();
    }

    // NEW: Get near expiry batches (within 7 days)
    public function getNearExpiryBatchesAttribute()
    {
        return $this->stockTransactions()
            ->where('type', 'in')
            ->whereNotNull('expired_date')
            ->whereBetween('expired_date', [now(), now()->addDays(7)])
            ->orderBy('expired_date', 'asc')
            ->get();
    }

    // NEW: Check if product has any expired batches
    public function getHasExpiredBatchesAttribute(): bool
    {
        return $this->stockTransactions()
            ->where('type', 'in')
            ->whereNotNull('expired_date')
            ->where('expired_date', '<', now())
            ->exists();
    }

    // NEW: Check if product has any near expiry batches
    public function getHasNearExpiryBatchesAttribute(): bool
    {
        return $this->stockTransactions()
            ->where('type', 'in')
            ->whereNotNull('expired_date')
            ->whereBetween('expired_date', [now(), now()->addDays(7)])
            ->exists();
    }

    public function getUnitTextAttribute(): string
    {
        return $this->unit === self::UNITS['KRG'] ? 'Krg' : 'Dus';
    }

    // Scopes (updated)
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('current_stock <= minimum_stock AND current_stock > 0');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('current_stock', '<=', 0);
    }

    // NEW: Scope for products with expired batches
    public function scopeHasExpiredBatches($query)
    {
        return $query->whereHas('stockTransactions', function ($q) {
            $q->where('type', 'in')
              ->whereNotNull('expired_date')
              ->where('expired_date', '<', now());
        });
    }

    // NEW: Scope for products with near expiry batches
    public function scopeHasNearExpiryBatches($query)
    {
        return $query->whereHas('stockTransactions', function ($q) {
            $q->where('type', 'in')
              ->whereNotNull('expired_date')
              ->whereBetween('expired_date', [now(), now()->addDays(7)]);
        });
    }
}
