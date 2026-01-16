<x-admin-layout title="Dashboard">
    <!-- Welcome Card -->
    <div class="card bg-primary text-white shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h4 class="fw-semibold mb-1">Selamat datang, {{ auth()->user()->name }}</h4>
                    <p class="mb-0">Anda telah login sebagai
                        <span class="badge bg-light text-primary">{{ auth()->user()->role }}</span>
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <p class="mb-0">{{ now()->format('l, d F Y') }}</p>
                    <small class="opacity-75">{{ now()->format('H:i') }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards Row -->
    <div class="row">
        <!-- Total Products Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Produk
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalProducts) }}</div>
                            <div class="text-xs text-muted">
                                Aktif: {{ number_format($activeProducts) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Categories Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Kategori
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalCategories) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tags fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Suppliers Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Supplier
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalSuppliers) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-truck fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Movement This Month Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pergerakan Bulan Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format(($stockInMovement->total_quantity ?? 0) + ($stockOutMovement->total_quantity ?? 0)) }}
                            </div>
                            <div class="text-xs text-muted">
                                Masuk: {{ number_format($stockInMovement->total_quantity ?? 0) }} |
                                Keluar: {{ number_format($stockOutMovement->total_quantity ?? 0) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exchange-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Alert Cards Row -->
    <div class="row">
        <!-- Low Stock Alert -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Stok Rendah
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($lowStockProducts) }}</div>
                            <div class="text-xs text-muted">
                                Produk perlu diisi ulang
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Out of Stock Alert -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Stok Habis
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($outOfStockProducts) }}</div>
                            <div class="text-xs text-muted">
                                Produk tidak tersedia
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products with Expired Batches -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Produk Kadaluarsa
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($productsWithExpiredBatches) }}</div>
                            <div class="text-xs text-muted">
                                Produk dengan Kelompok kadaluarsa
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products with Near Expiry Batches -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Hampir Kadaluarsa
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($productsWithNearExpiryBatches) }}</div>
                            <div class="text-xs text-muted">
                                mendekati Kadaluarsa
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Data Row -->
    <div class="row">
        <!-- Stock Movement Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Pergerakan Stok (30 Hari Terakhir)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="stockMovementChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Distribution -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Distribusi Kategori</h6>
                </div>
                <div class="card-body">
                    @forelse($categoryDistribution as $category)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-sm font-weight-bold">{{ $category->name }}</span>
                            <span class="badge bg-primary">{{ $category->products_count }}</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar" role="progressbar"
                                 style="width: {{ $totalProducts > 0 ? ($category->products_count / $totalProducts) * 100 : 0 }}%"
                                 aria-valuenow="{{ $category->products_count }}"
                                 aria-valuemin="0"
                                 aria-valuemax="{{ $totalProducts }}">
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted">
                        <p>Tidak ada kategori</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Data Tables Row -->
    <div class="row">
        <!-- Recent Transactions -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Transaksi Terakhir</h6>
                    <a href="{{ route('admin.stock-transactions.index') }}" class="btn btn-sm btn-primary">
                        Lihat Semua <i class="fas fa-arrow-right fa-sm"></i>
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Produk</th>
                                    <th>Tipe</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal</th>
                                    <th>User</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTransactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->transaction_code }}</td>
                                    <td>
                                        {{ $transaction->product->name }}
                                        @if($transaction->type === 'in' && $transaction->supplier)
                                            <br><small class="text-muted">dari {{ $transaction->supplier->name }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $transaction->type === 'in' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $transaction->type_text }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($transaction->quantity) }} {{ $transaction->product->unit_text }}</td>
                                    <td>{{ $transaction->formatted_transaction_date }}</td>
                                    <td>{{ $transaction->user->name }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Tidak ada transaksi</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Alerts and Critical Products -->
        <div class="col-xl-4 col-lg-5">
            <!-- Stock Alerts -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Peringatan Stok</h6>
                    @if($stockAlerts->count() > 0)
                    <span class="badge bg-danger">{{ $stockAlerts->count() }}</span>
                    @endif
                </div>
                <div class="card-body">
                    @forelse($stockAlerts->take(5) as $alert)
                    <div class="alert alert-{{ $alert->type === 'out_of_stock' ? 'danger' : 'warning' }} alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-{{ $alert->type === 'out_of_stock' ? 'times-circle' : 'exclamation-triangle' }}"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="fw-bold">{{ $alert->product->name }}</div>
                                <div class="small">{{ $alert->message }}</div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted">
                        <i class="fas fa-check-circle fa-3x mb-2"></i>
                        <p>Tidak ada peringatan stok</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Critical Stock Products -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Produk Stok Kritis</h6>
                </div>
                <div class="card-body">
                    @forelse($criticalStockProducts as $product)
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-{{ $product->current_stock <= 0 ? 'danger' : 'warning' }} text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas fa-{{ $product->current_stock <= 0 ? 'times' : 'exclamation' }}"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold">{{ $product->name }}</div>
                            <div class="small text-muted">
                                Stok: {{ $product->current_stock }} {{ $product->unit_text }}
                                @if($product->current_stock > 0)
                                    (Min: {{ $product->minimum_stock }})
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted">
                        <i class="fas fa-check-circle fa-3x mb-2"></i>
                        <p>Semua produk stok aman</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Expiry Monitoring Row -->
    @if($expiredBatches->count() > 0 || $nearExpiryBatches->count() > 0)
    <div class="row">
        <!-- Expired Batches -->
        @if($expiredBatches->count() > 0)
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-danger"> Kadaluarsa</h6>
                    <span class="badge bg-danger">{{ $expiredBatches->count() }}</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Produk</th>

                                    <th>Expired</th>
                                    <th>Supplier</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expiredBatches as $batch)
                                <tr>
                                    <td>{{ $batch->product->name }}</td>

                                    <td>{{ $batch->formatted_expired_date }}</td>
                                    <td>{{ $batch->supplier->name ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Near Expiry Batches -->
        @if($nearExpiryBatches->count() > 0)
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-warning">Batch Mendekati Kadaluarsa</h6>
                    <span class="badge bg-warning">{{ $nearExpiryBatches->count() }}</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Batch</th>
                                    <th>Expired</th>
                                    <th>Supplier</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($nearExpiryBatches as $batch)
                                <tr>
                                    <td>{{ $batch->product->name }}</td>

                                    <td>{{ $batch->formatted_expired_date }}</td>
                                    <td>{{ $batch->supplier->name ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>
        // Stock Movement Chart
        const ctx = document.getElementById('stockMovementChart').getContext('2d');
        const stockMovementChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Barang Masuk',
                    data: @json($stockInData),
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1
                }, {
                    label: 'Barang Keluar',
                    data: @json($stockOutData),
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</x-admin-layout>
