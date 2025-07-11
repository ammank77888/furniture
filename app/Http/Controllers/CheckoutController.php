<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\OrderCreated;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        return view('checkout', compact('cart'));
    }

    public function process(Request $request)
    {
        $cart = session('cart', []);
        // Ubah setiap produk di cart menjadi array sederhana
        $cart = array_map(function($item) {
            $product = $item['product'];
            if (is_object($product)) {
                $product = [
                    'id' => $product->_id ?? $product->id ?? null,
                    'name' => $product->name ?? null,
                    'price' => $product->price ?? null,
                ];
            }
            $item['product'] = $product;
            return $item;
        }, $cart);
        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'items' => $cart,
            'total' => array_sum(array_map(fn($item) => $item['product']['price'] * $item['qty'], $cart)),
            'status' => 'pending',
            'address' => $request->address,
        ]);
        session()->forget('cart');
        return redirect()->route('transactions.index');
    }
} 