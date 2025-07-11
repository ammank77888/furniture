<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background: #f8f9fa; }
        .sidebar {
            min-height: 100vh;
            background: #343a40;
            color: #fff;
        }
        .sidebar .nav-link {
            color: #fff; 
        }
        .sidebar .nav-link.active, .sidebar .nav-link:hover { 
            background: #495057; 
            color: #fff; 
        }
        .sidebar .sidebar-header { font-size: 1.5rem; 
            padding: 1rem; 
            text-align: center; 
            background: #23272b; 
        }
        .stat-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
        }
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }
        .chart-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 20px;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block sidebar">
            <div class="sidebar-header">Admin</div>
            <ul class="nav flex-column mb-4">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.produk.index') }}">Daftar Produk</a>
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
                <h1 class="mb-4">Admin Dashboard</h1>
                
                <!-- Statistik Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="stat-card p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Users</h6>
                                    <h3 class="mb-0">{{ number_format($totalUsers) }}</h3>
                                </div>
                                <div class="stat-icon bg-primary">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="stat-card p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Transaksi</h6>
                                    <h3 class="mb-0">{{ number_format($totalTransactions) }}</h3>
                                </div>
                                <div class="stat-icon bg-success">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="stat-card p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Pemasukan</h6>
                                    <h3 class="mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                                </div>
                                <div class="stat-icon bg-warning">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="stat-card p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Produk</h6>
                                    <h3 class="mb-0">{{ number_format($totalProducts) }}</h3>
                                </div>
                                <div class="stat-icon bg-info">
                                    <i class="fas fa-box"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Grafik Pemasukan -->
                <div class="chart-container">
                    <h5 class="mb-3">Grafik Pemasukan 7 Hari Terakhir</h5>
                    @if($hasTransactions)
                        <canvas id="revenueChart" width="400" height="200"></canvas>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-chart-line text-muted" style="font-size: 4rem;"></i>
                            <h5 class="text-muted mt-3">Belum ada transaksi</h5>
                            <p class="text-muted">Belum ada pemasukan dalam 7 hari terakhir</p>
                        </div>
                    @endif
                </div>
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

// Grafik Pemasukan
@if($hasTransactions)
const ctx = document.getElementById('revenueChart').getContext('2d');
const revenueData = @json($revenueData);

new Chart(ctx, {
    type: 'line',
    data: {
        labels: revenueData.map(item => item.date),
        datasets: [{
            label: 'Pemasukan (Rp)',
            data: revenueData.map(item => item.revenue),
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            },
            title: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                    }
                }
            }
        }
    }
});
@endif
</script>
</body>
</html> 