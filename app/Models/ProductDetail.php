<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductDetail extends Model
{
    use HasFactory;

    protected $table = 'product_details';
    protected $fillable = [
        'product_id',
        'price',
        'size',
        'color',
        'quantity',
        'image',
    ];

    public $timestamps = true;
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    // Trong model ProductDetail

}
