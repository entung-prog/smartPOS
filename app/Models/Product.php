<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'price',
        'stock',
        'category',
        'description',
        'image',
    ];

    protected $casts = [
        'price' => 'integer',
        'stock' => 'integer',
    ];

    public function transactionItems(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }
}