<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
//use Pest\Support\Str;

class Brand extends Model
{
    // Các thuộc tính có thể gán hàng loạt
    protected $fillable = [
        'name',    // Tên thương hiệu
        'slug',    // Đường dẫn thân thiện
        'status',  // Trạng thái (có thể là hiển thị/ẩn)
    ];
    protected static function booted()
    {
        static::creating(function ($brand) {
            $brand->slug = Str::slug($brand->name);
        });

        static::updating(function ($brand) {
            if ($brand->isDirty('name')) {
                $brand->slug = Str::slug($brand->name);
            }
        });
    }
    // Quan hệ: Một thương hiệu có nhiều sản phẩm
    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id');
    }

    public function banners()
    {
        return $this->hasMany(Product::class, 'brand_id');
    }
    // app/Models/Brand.php


}
