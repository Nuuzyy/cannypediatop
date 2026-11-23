<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'invoice_code',
        'product_item_id',
        'target_id',
        'server_id',
        'payment_method',
        'total_price',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'total_price' => 'integer',
        ];
    }

    public function productItem(): BelongsTo
    {
        return $this->belongsTo(ProductItem::class);
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'qris_simulation' => 'QRIS Simulasi',
            'virtual_account_simulation' => 'Virtual Account Simulasi',
            'ewallet_simulation' => 'E-Wallet Simulasi',
            default => $this->payment_method,
        };
    }
}
