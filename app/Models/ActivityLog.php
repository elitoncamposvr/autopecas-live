<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use hasFactory;

    protected $fillable = [
        'item_id',
        'user_id',
        'module',
        'action',
        'old_value',
        'new_value',
        'details',
    ];


    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
