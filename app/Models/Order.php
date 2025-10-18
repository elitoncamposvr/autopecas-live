<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client',
        'os_reference',
        'description',
        'notes',
        'price',
        'expected_delivery',
        'carrier',
        'status',
        'requester_id',
    ];

    // casts
    protected $casts = [
        'expected_delivery' => 'date',
        'price' => 'decimal:2',
    ];

    // relationships
    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function logs()
    {
        return $this->hasMany(OrderLog::class);
    }

    // helper scopes
    public function scopeStatus($query, $status)
    {
        if ($status && $status !== 'todos') {
            return $query->where('status', $status);
        }
        return $query;
    }
}
