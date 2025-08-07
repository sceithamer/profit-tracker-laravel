<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'storage_unit_id',
        'user_id',
        'platform_id',
        'item_name',
        'item_category',
        'sale_price',
        'sale_date',
        'fees',
        'shipping_cost'
    ];

    protected $casts = [
        'sale_date' => 'date',
        'sale_price' => 'decimal:2',
        'fees' => 'decimal:2',
        'shipping_cost' => 'decimal:2'
    ];

    public function storageUnit()
    {
        return $this->belongsTo(StorageUnit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    public function getNetSaleAttribute()
    {
        return $this->sale_price - $this->fees - $this->shipping_cost;
    }
}
