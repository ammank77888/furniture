<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
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
                    <li class="nav-item"><a class="nav-link text-dark" href="{{ route('cart.index') }}">Keranjang</a></li>
                    @auth
                        <li class="nav-item"><a class="nav-link text-dark" href="{{ route('recommendations.index') }}">Rekomendasi</a></li>
                        <li class="nav-item"><a class="nav-link text-dark" href="{{ route('transactions.index') }}">Riwayat</a></li>
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
    <h1>Checkout</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="address" class="form-label">Alamat Pengiriman</label>
            <textarea name="address" id="address" class="form-control" required></textarea>
        </div>
        <h4>Ringkasan Belanja</h4>
        <ul class="list-group mb-3">
            @php $total = 0; @endphp
            @foreach($cart as $item)
                @php $subtotal = $item['product']['price'] * $item['qty']; $total += $subtotal; @endphp
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $item['product']['name'] }} x {{ $item['qty'] }}
                    <span>Rp {{ number_format($subtotal,0,',','.') }}</span>
                </li>
            @endforeach
        </ul>
        <h5>Total: Rp {{ number_format($total,0,',','.') }}</h5>
        <button type="submit" class="btn btn-success">Proses Checkout</button>
    </form>
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