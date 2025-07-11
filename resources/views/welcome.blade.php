@php use Illuminate\Support\Str; @endphp
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Beranda - Toko Furniture</title>
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
            .product-card {
                background: #fff;
                border-radius: 12px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.07);
                transition: transform 0.2s;
                height: 100%;
                display: flex;
                flex-direction: column;
            }
            .product-card:hover {
                transform: translateY(-4px) scale(1.01);
                box-shadow: 0 4px 16px rgba(0,0,0,0.12);
            }
            .product-image-container {
                background: #f8f9fa;
                border-radius: 12px 12px 0 0;
                display: flex;
                align-items: center;
                justify-content: center;
                height: 210px;
                overflow: hidden;
            }
            .product-image {
                max-width: 100%;
                max-height: 190px;
                object-fit: contain;
                background: #f8f9fa;
            }
            .product-card .card-body, .product-card .p-3 {
                flex: 1 1 auto;
                display: flex;
                flex-direction: column;
            }
        </style>
        <div class="container mt-4">
            <h1>Selamat Datang di Toko Furniture</h1>
            <h3>Produk Unggulan</h3>
            <div class="row">
                @foreach($products as $product)
                    <div class="col-md-3 mb-4">
                        <div class="product-card position-relative h-100">
                            <div class="product-image-container">
                                <img src="@if(!empty($product->image_path) && (Str::startsWith($product->image_path, 'http://') || Str::startsWith($product->image_path, 'https://'))){{ $product->image_path }}@elseif(!empty($product->image_path) && file_exists(public_path('storage/'.$product->image_path))){{ asset('storage/'.$product->image_path) }}@elseif(!empty($product->image)){{ $product->image }}@else https://via.placeholder.com/300x200?text=No+Image @endif" class="product-image" alt="{{ $product->name }}">
                            </div>
                            <div class="p-3 d-flex flex-column">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="text-muted small flex-grow-1">{{ Str::limit($product->description, 60) }}</p>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="h5 text-primary mb-0">Rp {{ number_format($product->price,0,',','.') }}</span>
                                    <div class="text-muted small">
                                        <i class="fas fa-eye"></i> {{ $product->view_count ?? 0 }}
                                        <i class="fas fa-heart ms-2"></i> {{ $product->like_count ?? 0 }}
                                    </div>
                                </div>
                                <div class="d-flex gap-2 mt-auto">
                                    <a href="{{ route('produk.detail', $product->_id) }}" class="btn btn-outline-primary btn-sm flex-fill">Detail</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <a href="{{ route('katalog') }}" class="btn btn-outline-primary">Lihat Semua Produk</a>
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
