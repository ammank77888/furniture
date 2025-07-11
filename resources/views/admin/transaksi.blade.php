<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Transaksi - Admin</title>
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
                    <a class="nav-link" href="{{ route('admin.produk.index') }}">Daftar Produk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('admin.transaksi.index') }}">Daftar Transaksi</a>
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
                <h1>Daftar Transaksi</h1>
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if(!request()->routeIs('admin.transaksi.edit') && !request()->routeIs('admin.transaksi.create'))
                <form class="row g-3 mb-4" method="GET" action="{{ route('admin.transaksi.index') }}">
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="q" placeholder="Cari berdasarkan nama user..." value="{{ request('q') }}">
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="form-select">
                            <option value="">- Semua Status -</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('admin.transaksi.index') }}" class="btn btn-secondary w-100">Reset</a>
                    </div>
                </form>
                @endif
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = ($transactions->currentPage() - 1) * $transactions->perPage() + 1; @endphp
                        @forelse($transactions as $trx)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $trx->user->name ?? '-' }}</td>
                                <td>Rp {{ number_format($trx->total,0,',','.') }}</td>
                                <td>
                                    <span class="badge bg-{{ $trx->status === 'pending' ? 'secondary' : ($trx->status === 'paid' ? 'info' : ($trx->status === 'shipped' ? 'primary' : ($trx->status === 'completed' ? 'success' : 'danger'))) }}">{{ $trx->status }}</span>
                                </td>
                                <td>{{ $trx->created_at ? $trx->created_at->format('d-m-Y H:i') : '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.transaksi.edit', $trx->_id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('admin.transaksi.destroy', $trx->_id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus transaksi ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center">Tidak ada transaksi ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $transactions->links() }}
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