<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand text-dark fw-bold" href="{{ route('home') }}">Furniture Store</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link text-dark" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="{{ route('katalog') }}">Katalog</a></li>
                    @auth
                        <li class="nav-item"><a class="nav-link text-dark" href="{{ route('cart.index') }}">Keranjang</a></li>
                        <li class="nav-item"><a class="nav-link text-dark" href="{{ route('recommendations.index') }}">Rekomendasi</a></li>
                        <li class="nav-item"><a class="nav-link active text-dark" href="{{ route('transactions.index') }}">Riwayat</a></li>
                        @if(Auth::user()->role === 'admin')
                            <li class="nav-item"><a class="nav-link text-dark" href="{{ route('admin.dashboard') }}">Admin</a></li>
                        @endif
                    @endauth
                </ul>
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item"><a class="nav-link text-dark" href="{{ route('login') }}">Login</a></li>
                        <li class="nav-item"><a class="nav-link text-dark" href="{{ route('register') }}">Register</a></li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-dark" href="#" role="button" data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                            </ul>
                        </li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    <style>
        .navbar { background: #fff !important; }
        .navbar-brand, .navbar-nav .nav-link { color: #212529 !important; font-weight: 500; }
        .navbar-nav .nav-link.active, .navbar-nav .nav-link:hover { color: #0d6efd !important; }
    </style>
<div class="container mt-4">
    <h1>Riwayat Transaksi</h1>
    @if(count($transactions) > 0)
    <table class="table">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Status</th>
                <th>Alamat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $trx)
                <tr>
                    <td>{{ $trx->created_at ? $trx->created_at->format('d-m-Y H:i') : '-' }}</td>
                    <td>
                        @if(isset($trx->items) && is_array($trx->items) && count($trx->items) > 0)
                            <div class="mb-0 ps-3">
                                @foreach($trx->items as $item)
                                    <div>{{ $item['product']['name'] ?? '-' }}</div>
                                @endforeach
                            </div>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if(isset($trx->items) && is_array($trx->items) && count($trx->items) > 0)
                            <div class="mb-0 ps-3">
                                @foreach($trx->items as $item)
                                    <div>{{ $item['qty'] ?? '-' }}</div>
                                @endforeach
                            </div>
                        @else
                            -
                        @endif
                    </td>
                    <td>Rp {{ number_format($trx->total,0,',','.') }}</td>
                    <td>{{ $trx->status }}
                        @if($trx->status === 'pending')
                            <form action="{{ route('transactions.confirm', $trx->_id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success ms-2">Konfirmasi Pembayaran</button>
                            </form>
                        @endif
                    </td>
                    <td>{{ $trx->address }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $transactions->links() }}
    @else
        <p>Belum ada transaksi.</p>
    @endif
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
@if(session('success'))
  toastr.success("{{ session('success') }}");
@endif
@if(session('error'))
  toastr.error("{{ session('error') }}");
@endif
</script>
</body>
</html> 