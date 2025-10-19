<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'item_id',
        'supplier_id',
        'brand',
        'unit_price',
        'quantity',
        'included_in_purchase',
        'valid_until',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'included_in_purchase' => 'boolean',
        'valid_until' => 'date',
    ];

    // Relationships
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Accessor for total value
    public function getTotalValueAttribute(): float
    {
        return $this->unit_price * $this->quantity;
    }
}
