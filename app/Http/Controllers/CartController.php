<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = session('cart', []);
        return view('keranjang', compact('cart'));
    }

    public function add(Request $request, $id)
    {
        $cart = session('cart', []);
        $product = Product::find($id);
        $cart[$id] = [
            'product' => $product,
            'qty' => ($cart[$id]['qty'] ?? 0) + 1
        ];
        session(['cart' => $cart]);
        return redirect()->route('cart.index');
    }

    public function remove(Request $request, $id)
    {
        $cart = session('cart', []);
        unset($cart[$id]);
        session(['cart' => $cart]);
        return redirect()->route('cart.index');
    }
} 