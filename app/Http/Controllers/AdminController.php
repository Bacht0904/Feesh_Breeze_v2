<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function products()
    {
        return view('admin.products');
    }

    public function add_product()
    {
        return view('admin.product-add');
    }

    public function brands()
    {
        return view('admin.brands');
    }

    public function add_brand()
    {
        return view('admin.brand-add');
    }

    public function categories()
    {
        return view('admin.categories');
    }

    public function add_category()
    {
        return view('admin.category-add');
    }

        public function orders()
    {
        return view('admin.orders');
    }

    public function order_detail()
    {
        return view('admin.order-detail');
    }

    public function order_tracking()
    {
        return view('admin.order-tracking');
    }

    public function sliders()
    {
        return view('admin.sliders');
    }

    public function add_slide()
    {
        return view('admin.slide-add');
    }

    public function users()
    {
        return view('admin.users');
    }

    public function coupons()
    {
        return view('admin.coupons');
    }

    public function add_coupon()
    {
        return view('admin.coupon-add');
    }

        public function settings()
    {
        return view('admin.settings');
    }
}