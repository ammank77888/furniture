<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Mail\OrderStatusUpdated;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        // Statistik untuk cards
        $totalUsers = \App\Models\User::count();
        $totalTransactions = Transaction::count();
        $totalRevenue = Transaction::where('status', '!=', 'cancelled')->sum('total');
        $totalProducts = Product::count();

        // Data untuk grafik pemasukan (7 hari terakhir)
        $revenueData = [];
        $hasTransactions = false;
        
        for ($i = 6; $i >= 0; $i--) {
            $startDate = now()->subDays($i)->startOfDay();
            $endDate = now()->subDays($i)->endOfDay();
            
            $dailyRevenue = Transaction::where('status', '!=', 'cancelled')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('total');
            
            if ($dailyRevenue > 0) {
                $hasTransactions = true;
            }
            
            $revenueData[] = [
                'date' => now()->subDays($i)->format('d M'),
                'revenue' => $dailyRevenue
            ];
        }

        return view('admin.dashboard', compact('totalUsers', 'totalTransactions', 'totalRevenue', 'totalProducts', 'revenueData', 'hasTransactions'));
    }

    public function produkIndex(Request $request)
    {
        $productQuery = Product::query();
        if ($request->filled('q')) {
            $productQuery->where('name', 'like', '%'.$request->q.'%');
        }
        if ($request->filled('min_price')) {
            $productQuery->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $productQuery->where('price', '<=', $request->max_price);
        }
        $products = $productQuery->paginate(10)->appends($request->except('page'));
        return view('admin.produk', compact('products'));
    }

    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'description' => 'nullable',
            'image' => 'nullable|url',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $data = $request->only(['name','price','stock','description','image']);
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('produk', 'public');
            $data['image_path'] = $path;
        }
        Product::create($data);
        return redirect()->route('admin.dashboard')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit($id)
    {
        $product = Product::find($id);
        return view('admin.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'description' => 'nullable',
            'image' => 'nullable|url',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $product = Product::find($id);
        $data = $request->only(['name','price','stock','description','image']);
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('produk', 'public');
            $data['image_path'] = $path;
        }
        $product->update($data);
        return redirect()->route('admin.dashboard')->with('success', 'Produk berhasil diupdate');
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Produk berhasil dihapus');
    }

    public function editTransaction($id)
    {
        $transaction = Transaction::find($id);
        return view('admin.edit_transaction', compact('transaction'));
    }

    public function updateTransaction(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
        ]);
        $transaction = Transaction::find($id);
        $transaction->status = $request->status;
        $transaction->save();
        return redirect()->route('admin.dashboard')->with('success', 'Status transaksi berhasil diupdate');
    }

    public function destroyTransaction($id)
    {
        $transaction = Transaction::find($id);
        $transaction->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Transaksi berhasil dihapus');
    }

    public function listTransaction(Request $request)
    {
        $trxQuery = Transaction::query();
        
        if ($request->filled('q')) {
            // Cari berdasarkan nama user
            $searchTerm = $request->q;
            $trxQuery->whereHas('user', function($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%');
            });
        }
        
        if ($request->filled('status')) {
            $trxQuery->where('status', $request->status);
        }
        
        $transactions = $trxQuery->with('user')->orderByDesc('created_at')->paginate(10)->appends($request->except('page'));
        return view('admin.transaksi', compact('transactions'));
    }
} 