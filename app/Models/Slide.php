<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    protected $fillable = [
        'title',
        'image',
        'description',
        'status',
    ];

    // Scope lấy các slide đang active
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
