<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
