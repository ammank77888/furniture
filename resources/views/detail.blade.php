@php use Illuminate\Support\Str; @endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk</title>
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
    </style>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-6">
            <img src="@if(!empty($product->image_path) && (Str::startsWith($product->image_path, 'http://') || Str::startsWith($product->image_path, 'https://'))){{ $product->image_path }}@elseif(!empty($product->image_path) && file_exists(public_path('storage/'.$product->image_path))){{ asset('storage/'.$product->image_path) }}@elseif(!empty($product->image)){{ $product->image }}@else https://via.placeholder.com/300 @endif" class="img-fluid" alt="{{ $product->name }}">
        </div>
        <div class="col-md-6">
            <h1>{{ $product->name }}</h1>
            <h3>Rp {{ number_format($product->price,0,',','.') }}</h3>
            <p>{{ $product->description }}</p>
            <div class="mb-3">
                <small class="text-muted">
                    <i class="fas fa-eye"></i> <span class="view-count">{{ $product->view_count ?? 0 }}</span> dilihat
                    <i class="fas fa-heart ms-3"></i> <span class="like-count">{{ $product->like_count ?? 0 }}</span> disukai
                </small>
            </div>
            <form action="{{ route('cart.add', $product->_id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success" onclick="trackActivity('{{ $product->_id }}', 'cart')">
                    <i class="fas fa-cart-plus"></i> Tambah ke Keranjang
                </button>
            </form>
            @auth
                <button id="likeButton" class="btn btn-outline-danger mt-2" onclick="trackActivity('{{ $product->_id }}', 'like')">
                    <i class="fas fa-heart"></i> <span id="likeText">Suka</span>
                </button>
            @endauth
        </div>
    </div>
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

// Track activity when page loads
document.addEventListener('DOMContentLoaded', function() {
    @auth
        trackActivity('{{ $product->_id }}', 'view');
        checkLikeStatus('{{ $product->_id }}');
    @endauth
});

function checkLikeStatus(productId) {
    fetch('{{ route("recommendations.track") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            product_id: productId,
            activity_type: 'check_like'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.is_liked) {
            updateLikeButton(true);
        }
    })
    .catch(error => {});
}

function updateLikeButton(isLiked) {
    const button = document.getElementById('likeButton');
    const likeText = document.getElementById('likeText');
    
    if (isLiked) {
        button.classList.remove('btn-outline-danger');
        button.classList.add('btn-danger');
        likeText.textContent = 'Disukai';
        button.disabled = true; // Disable button setelah like
    } else {
        button.classList.remove('btn-danger');
        button.classList.add('btn-outline-danger');
        likeText.textContent = 'Suka';
        button.disabled = false;
    }
}

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
            // Update stats di halaman
            if (data.product_stats) {
                const viewCount = document.querySelector('.view-count');
                const likeCount = document.querySelector('.like-count');
                if (viewCount) viewCount.textContent = data.product_stats.view_count;
                if (likeCount) likeCount.textContent = data.product_stats.like_count;
            }
            
            if (activityType === 'like') {
                // Update like button status
                updateLikeButton(data.liked);
            }
        } else {
            console.error('Error:', data.error);
        }
    })
    .catch(error => {
        console.error('Error tracking activity:', error);
    });
}
</script>
</body>
</html> 