<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::where('user_id', Auth::id())->paginate(10);
        return view('riwayat', compact('transactions'));
    }

    public function confirm($id)
    {
        $transaction = Transaction::where('_id', $id)->where('user_id', auth()->id())->firstOrFail();
        if ($transaction->status === 'pending') {
            // Kurangi stok produk
            if (isset($transaction->items) && is_array($transaction->items)) {
                foreach ($transaction->items as $item) {
                    $productId = $item['product']['id'] ?? $item['product']['_id'] ?? null;
                    if ($productId) {
                        $product = \App\Models\Product::find($productId);
                        if ($product) {
                            $product->stock = max(0, $product->stock - ($item['qty'] ?? 1));
                            $product->save();
                        }
                    }
                }
            }
            $transaction->status = 'paid';
            $transaction->save();
            return redirect()->route('transactions.index')->with('success', 'Pembayaran berhasil dikonfirmasi.');
        }
        return redirect()->route('transactions.index')->with('error', 'Transaksi tidak valid atau sudah dikonfirmasi.');
    }
} 