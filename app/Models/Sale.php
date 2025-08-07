<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    protected $fillable = [
        'storage_unit_id',
        'user_id',
        'platform_id',
        'category_id',
        'item_name',
        'item_category', // Keep temporarily during migration
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

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getNetSaleAttribute()
    {
        return $this->sale_price - $this->fees - $this->shipping_cost;
    }

    /**
     * Get the category name for this sale (fallback to item_category if no category relationship).
     */
    public function getCategoryNameAttribute(): string
    {
        return $this->category?->name ?? $this->item_category ?? 'Uncategorized';
    }

    /**
     * Scope to filter sales by category.
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope to include category relationship.
     */
    public function scopeWithCategory($query)
    {
        return $query->with('category');
    }
}
