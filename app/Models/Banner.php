<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        "title",
        "image",
        "brand_id",
        "status",
    ];
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }
}
