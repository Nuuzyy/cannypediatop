<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'category',
        'publisher',
        'target_label',
        'has_server_id',
        'image_url',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'has_server_id' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function items(): HasMany
    {
        return $this->hasMany(ProductItem::class);
    }
}
