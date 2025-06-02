<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function welcome()
    {
        return view('welcome');
    }
      public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }
    public function index()
    {
        if (Auth::check()) {
            return view('welcome');
        } else {
            return redirect()->route('welcome');
        }
    }
    public function about()
    {
        return view('user.about');
    }
    public function shop()
    {
        return view('user.shop');
    }
    public function contact()
    {
        return view('user.contact');
    }
    public function cart()
    {
        return view('user.cart');
    }
    public function wishlist()
    {
        return view('user.wishlist');
    }
    public function checkout()
    {
        return view('user.checkout');
    }
    public function profile()
    {
        return view('user.profile');
    }
}
