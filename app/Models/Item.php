<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Item extends Model
{
    use hasFactory, softDeletes;

    public const STATUS_QUOTING = 'quoting';
    public const STATUS_NEGOTIATING = 'negotating';
    public const STATUS_PURCHASED = 'purchased';
    public const STATUS_FINALIZED = 'finalized';

    protected $fillable = [
        'description',
        'brand_desired',
        'item_code',
        'notes',
        'required_quantity',
        'status',
        'created_by',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->created_by) && Auth::check()) {
                $model->created_by = Auth::id();
            }
        });
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
