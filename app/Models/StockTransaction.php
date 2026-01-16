<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockTransaction extends Model
{
    use HasFactory;

    public const TYPES = [
        'IN' => 'in',
        'OUT' => 'out',
    ];

    protected $fillable = [
        'transaction_code',
        'product_id',
        'supplier_id',
        'user_id',
        'type',
        'quantity',
        'unit_price',      // Only for 'in' transactions
        'total_price',     // Only for 'in' transactions
        'expired_date',    // Only for 'in' transactions

        'stock_before',
        'stock_after',
        'notes',
        'transaction_date',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'stock_before' => 'integer',
        'stock_after' => 'integer',
        'transaction_date' => 'date',
        'expired_date' => 'date',
    ];

    // Relationships
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Accessors
    public function getTypeTextAttribute(): string
    {
        return $this->type === self::TYPES['IN'] ? 'Masuk' : 'Keluar';
    }

    public function getFormattedTransactionDateAttribute(): string
    {
        return $this->transaction_date->format('d/m/Y');
    }

    public function getFormattedExpiredDateAttribute(): ?string
    {
        return $this->expired_date ? $this->expired_date->format('d/m/Y') : null;
    }

    public function getHasPriceInfoAttribute(): bool
    {
        return $this->type === self::TYPES['IN'] && !is_null($this->total_price);
    }

    // NEW: Check if transaction has expired date
    public function getHasExpiredDateAttribute(): bool
    {
        return $this->type === self::TYPES['IN'] && !is_null($this->expired_date);
    }

    // NEW: Check if batch is expired
    public function getIsExpiredAttribute(): bool
    {
        return $this->expired_date && $this->expired_date->isPast();
    }

    // NEW: Check if batch is near expiry
    public function getIsNearExpiryAttribute(): bool
    {
        if (!$this->expired_date) {
            return false;
        }

        return $this->expired_date->diffInDays(now()) <= 7 && $this->expired_date->isFuture();
    }

    // NEW: Get expiry status
    public function getExpiryStatusAttribute(): string
    {
        if (!$this->expired_date) {
            return 'no_expiry';
        }

        if ($this->expired_date->isPast()) {
            return 'expired';
        }

        if ($this->expired_date->diffInDays(now()) <= 7) {
            return 'near_expiry';
        }

        return 'normal';
    }

    // Scopes
    public function scopeStockIn($query)
    {
        return $query->where('type', self::TYPES['IN']);
    }

    public function scopeStockOut($query)
    {
        return $query->where('type', self::TYPES['OUT']);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('transaction_date', [$startDate, $endDate]);
    }

    // NEW: Scope for expired batches
    public function scopeExpired($query)
    {
        return $query->where('type', self::TYPES['IN'])
            ->whereNotNull('expired_date')
            ->where('expired_date', '<', now());
    }

    // NEW: Scope for near expiry batches
    public function scopeNearExpiry($query)
    {
        return $query->where('type', self::TYPES['IN'])
            ->whereNotNull('expired_date')
            ->whereBetween('expired_date', [now(), now()->addDays(7)]);
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->transaction_code)) {
                $model->transaction_code = self::generateTransactionCode($model->type);
            }

            // HAPUS BAGIAN INI - tidak perlu kalkulasi lagi
            // if ($model->type === self::TYPES['IN'] && $model->unit_price && $model->quantity) {
            //     $model->total_price = $model->unit_price * $model->quantity;
            // }
        });
    }

    private static function generateTransactionCode($type): string
    {
        $prefix = $type === self::TYPES['IN'] ? 'IN' : 'OUT';
        $date = now()->format('Ymd');
        $lastTransaction = self::where('type', $type)
            ->whereDate('created_at', now())
            ->latest()
            ->first();

        $sequence = $lastTransaction ?
            intval(substr($lastTransaction->transaction_code, -3)) + 1 : 1;

        return sprintf('%s%s%03d', $prefix, $date, $sequence);
    }
}
