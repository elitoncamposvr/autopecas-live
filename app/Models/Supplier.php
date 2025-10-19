<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use hasFactory, softDeletes;

    protected $fillable = [
        'name',
        'contact',
        'phone',
        'cnpj',
        'email',
        'address',
        'notes',
    ];

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }
}
