<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Product_detail;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
      public function products()
    {
        $products = Product::with(['category', 'brand'])->get();

        $products = Product::with(['product_details'])->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.products', compact('products'));
    }
}
