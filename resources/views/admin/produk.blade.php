@php use Illuminate\Support\Str; @endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Produk - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        body { background: #f8f9fa; }
        .sidebar {
            min-height: 100vh;
            background: #343a40;
            color: #fff;
        }
        .sidebar .nav-link { color: #fff; }
        .sidebar .nav-link.active, .sidebar .nav-link:hover { background: #495057; color: #fff; }
        .sidebar .sidebar-header { font-size: 1.5rem; padding: 1rem; text-align: center; background: #23272b; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block sidebar">
            <div class="sidebar-header">Admin</div>
            <ul class="nav flex-column mb-4">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('admin.produk.index') }}">Daftar Produk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.transaksi.index') }}">Daftar Transaksi</a>
                </li>
                <li class="nav-item mt-4">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-danger w-100" type="submit">Logout</button>
                    </form>
                </li>
            </ul>
        </nav>
        <main class="col-md-10 ms-sm-auto px-4">
            <div class="pt-4">
                <h1>Daftar Produk</h1>
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <form class="row g-3 mb-4" method="GET" action="{{ route('admin.produk.index') }}">
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="q" placeholder="Cari nama produk..." value="{{ request('q') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="number" class="form-control" name="min_price" placeholder="Harga min" value="{{ request('min_price') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="number" class="form-control" name="max_price" placeholder="Harga max" value="{{ request('max_price') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary w-100">Reset</a>
                    </div>
                </form>
                <a href="{{ route('admin.produk.create') }}" class="btn btn-success mb-3">Tambah Produk</a>
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-center">Gambar</th>
                            <th class="text-center">Nama</th>
                            <th class="text-center">Harga</th>
                            <th class="text-center">Stok</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td class="text-center align-middle">
                                    @if(!empty($product->image_path) && (Str::startsWith($product->image_path, 'http://') || Str::startsWith($product->image_path, 'https://')))
                                        <img src="{{ $product->image_path }}" alt="Gambar" style="max-width:60px;max-height:60px;">
                                    @elseif(!empty($product->image_path) && file_exists(public_path('storage/'.$product->image_path)))
                                        <img src="{{ asset('storage/'.$product->image_path) }}" alt="Gambar" style="max-width:60px;max-height:60px;">
                                    @elseif(!empty($product->image))
                                        <img src="{{ $product->image }}" alt="Gambar" style="max-width:60px;max-height:60px;">
                                    @else
                                        <img src="https://via.placeholder.com/60" alt="No Image" style="max-width:60px;max-height:60px;">
                                    @endif
                                </td>
                                <td class="text-center align-middle">{{ $product->name }}</td>
                                <td class="text-center align-middle">Rp {{ number_format($product->price,0,',','.') }}</td>
                                <td class="text-center align-middle">{{ $product->stock }}</td>
                                <td class="text-center align-middle">
                                    <a href="{{ route('admin.produk.edit', $product->_id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('admin.produk.destroy', $product->_id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus produk ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $products->links() }}
            </div>
        </main>
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
</script>
</body>
</html> 