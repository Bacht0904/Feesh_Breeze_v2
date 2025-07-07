<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product_details extends Model
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
        return $this->belongsTo(Product::class,'product_id');
    }
    // Trong model ProductDetail
    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class,'product_detail_id');
    }

}
