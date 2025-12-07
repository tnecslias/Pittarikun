<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart_items = Auth::user()->cart_items ?? [];
        return view('cart.index', compact('cart_items'));
    }
}
