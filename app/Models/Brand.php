<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    // Các thuộc tính có thể gán hàng loạt
    protected $fillable = [
        'name',    // Tên thương hiệu
        'slug',    // Đường dẫn thân thiện
        'status',  // Trạng thái (có thể là hiển thị/ẩn)
    ];

    // Quan hệ: Một thương hiệu có nhiều sản phẩm
    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id');
    }

    public function banners()
    {
        return $this->hasMany(Product::class, 'brand_id');
    }
}