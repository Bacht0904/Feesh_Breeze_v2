<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'status',
        'parent_id',
    ];

    public function parent()
    {
        return $this->belongsTo('App\Models\Category', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('App\Models\Category', 'parent_id');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product', 'cat_id');
    }
    public static function getCategoryBySlug($slug)
    {
        return Category::with(['children', 'products'])->where('slug', $slug)->first();
    }
    public static function getCategoryById($id)
    {
        return Category::with(['children', 'products'])->where('id', $id)->first();
    }
    public static function getCategoryByParentId($parentId)
    {
        return Category::with(['children', 'products'])->where('parent_id', $parentId)->get();
    }
    public static function getAllCategories()
    {
        return Category::with(['children', 'products'])->get();
    }
    public static function getAllCategoriesWithProducts()
    {
        return Category::with(['children', 'products'])->get();
    }
    public static function getCategoryByParentIdWithProducts($parentId)
    {
        return Category::with(['children', 'products'])->where('parent_id', $parentId)->get();
    }
    public static function getCategoryByIdWithProducts($id)
    {
        return Category::with(['children', 'products'])->where('id', $id)->first();
    }
}
