<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekomendasi Produk - Furniture Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #f8f9fa; }
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
        .product-card .p-3 {
            flex: 1 1 auto;
            display: flex;
            flex-direction: column;
        }
        .recommendation-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #28a745;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
        }
    </style>
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
                    <li class="nav-item"><a class="nav-link text-dark" href="{{ route('home') }}">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="{{ route('katalog') }}">Katalog</a></li>
                    @auth
                        <li class="nav-item"><a class="nav-link text-dark" href="{{ route('cart.index') }}">Keranjang</a></li>
                        <li class="nav-item"><a class="nav-link active text-dark" href="{{ route('recommendations.index') }}">Rekomendasi</a></li>
                        <li class="nav-item"><a class="nav-link text-dark" href="{{ route('transactions.index') }}">Riwayat</a></li>
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

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4">
                    @if($recommendationType === 'personalized')
                        <i class="fas fa-heart text-danger"></i> Rekomendasi untuk Anda
                    @else
                        <i class="fas fa-fire text-warning"></i> Produk Populer
                    @endif
                </h1>
                
                @if($recommendationType === 'personalized')
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        Rekomendasi ini berdasarkan produk yang Anda sukai, riwayat pembelian, dan produk yang Anda lihat.
                    </div>
                @endif

                @if($recommendations->count() > 0)
                    <div class="row">
                        @foreach($recommendations as $product)
                            <div class="col-md-3 mb-4">
                                <div class="product-card position-relative">
                                    @if($recommendationType === 'personalized')
                                        <div class="recommendation-badge">
                                            <i class="fas fa-star"></i> Direkomendasikan
                                        </div>
                                    @endif
                                    
                                    <div class="product-image-container">
                                        <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : ($product->image ?: 'https://via.placeholder.com/300x200?text=No+Image') }}" 
                                             class="product-image" alt="{{ $product->name }}">
                                    </div>
                                    
                                    <div class="p-3">
                                        <h5 class="card-title">{{ $product->name }}</h5>
                                        <p class="text-muted small">{{ Str::limit($product->description, 100) }}</p>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="h5 text-primary mb-0">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                            <div class="text-muted small">
                                                <i class="fas fa-eye"></i> {{ $product->view_count ?? 0 }}
                                                <i class="fas fa-heart ms-2"></i> {{ $product->like_count ?? 0 }}
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('produk.detail', $product->_id) }}" 
                                               class="btn btn-outline-primary btn-sm flex-fill"
                                               onclick="trackActivity('{{ $product->_id }}', 'view')">
                                                <i class="fas fa-eye"></i> Lihat Detail
                                            </a>
                                                                                         @auth
                                                 <button class="btn btn-outline-danger btn-sm like-btn" 
                                                         data-product-id="{{ $product->_id }}"
                                                         onclick="trackActivity('{{ $product->_id }}', 'like')">
                                                     <i class="fas fa-heart"></i>
                                                 </button>
                                             @endauth
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-search text-muted" style="font-size: 4rem;"></i>
                        <h4 class="text-muted mt-3">Belum ada rekomendasi</h4>
                        <p class="text-muted">Mulai berbelanja untuk mendapatkan rekomendasi personal</p>
                        <a href="{{ route('katalog') }}" class="btn btn-primary">Lihat Katalog</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function trackActivity(productId, activityType) {
            fetch('{{ route("recommendations.track") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: productId,
                    activity_type: activityType
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (activityType === 'like') {
                        // Update like button status
                        const button = event.target.closest('button');
                        if (data.liked) {
                            button.classList.remove('btn-outline-danger');
                            button.classList.add('btn-danger');
                            button.disabled = true;
                        }
                        
                    }
                } else {
                    console.error('Error:', data.error);
                }
            })
            .catch(error => {});
        }
    </script>
</body>
</html> 