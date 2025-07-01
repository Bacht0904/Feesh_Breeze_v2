<?php

namespace App\Models;

use App\Models\ProductDetail;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class Product extends Model
{

    protected $fillable = [
        "name",
        "slug",
        "category_id",
        "brand_id",
        "description",
    ];



    public function product_details()
    {
        return $this->hasMany(ProductDetail::class, 'product_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

}
