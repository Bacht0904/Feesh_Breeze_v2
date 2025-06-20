<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product_details extends Model
{
    protected $fillable = [
        'product_id',
        'price',
        'size',
        'color',
        'quantity',
        'image',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
