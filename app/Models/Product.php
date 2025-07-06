<?php

namespace App\Models;

use App\Models\Product_details;
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
        return $this->hasMany(Product_details::class, 'product_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function brand()
    {

        return $this->belongsTo(Brand::class, 'brand_id');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class)->where('status', 1);
    }
    public function lowestPricedDetail()
    {
        return $this->hasOne(Product_details::class)
            ->where('quantity', '>', 0)
            ->orderBy('price')        // Giá thấp nhất trước
            ->orderBy('size')         // Size nhỏ nhất tiếp theo (nếu có)
            ->orderByRaw('RAND()');   // Chọn màu ngẫu nhiên nếu trùng

    }
    public function order_details()
    {
        return $this->hasManyThrough(
            \App\Models\OrderDetail::class,
            \App\Models\Product_details::class,
            'product_id',          // Khóa ngoại trên bảng product_details
            'product_detail_id',   // Khóa ngoại trên bảng order_details
            'id',                  // Khóa chính của bảng products
            'id'                   // Khóa chính của bảng product_details
        );
    }
}
