<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'storage_unit_id',
        'description',
        'amount',
        'date',
        'category'
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2'
    ];

    public function storageUnit()
    {
        return $this->belongsTo(StorageUnit::class);
    }
}
