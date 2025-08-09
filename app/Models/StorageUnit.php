<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StorageUnit extends Model
{
    protected $fillable = [
        'name',
        'purchase_date', 
        'cost',
        'location',
        'notes',
        'status'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'cost' => 'decimal:2'
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function getGrossRevenueAttribute()
    {
        return $this->sales->sum('sale_price');
    }

    public function getTotalExpensesAttribute()
    {
        return $this->cost + 
               $this->sales->sum('fees') + 
               $this->sales->sum('shipping_cost') + 
               $this->expenses->sum('amount');
    }

    public function getNetRevenueAttribute()
    {
        return $this->gross_revenue - $this->total_expenses;
    }

    public function getRoiAttribute()
    {
        return $this->total_expenses > 0 
            ? round(($this->net_revenue / $this->total_expenses) * 100, 2) 
            : 0;
    }

    // Legacy methods for backward compatibility
    public function getTotalSalesAttribute()
    {
        return $this->sales->sum(function ($sale) {
            return $sale->sale_price - $sale->fees - $sale->shipping_cost;
        });
    }

    public function getNetProfitAttribute()
    {
        return $this->net_revenue;
    }
}
