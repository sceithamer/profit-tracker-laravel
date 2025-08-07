<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name'
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();
        
        // Prevent deletion of categories that have associated sales
        static::deleting(function ($category) {
            if ($category->sales()->count() > 0) {
                throw new \Exception(
                    "Cannot delete category '{$category->name}' because it has associated sales. " .
                    "Please reassign the sales to another category first."
                );
            }
        });
    }

    /**
     * A category has many sales.
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Get the sales count for this category.
     */
    public function getSalesCountAttribute(): int
    {
        return $this->sales()->count();
    }

    /**
     * Scope to order categories by name alphabetically.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('name');
    }

    /**
     * Scope to get categories with sales count.
     */
    public function scopeWithSalesCount($query)
    {
        return $query->withCount('sales');
    }
}
