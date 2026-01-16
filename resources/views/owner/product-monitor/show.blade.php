<x-admin-layout title="Monitor Produk - {{ $product->name }}">
    <!-- Breadcrumb -->
    <div class="container-fluid">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-light p-3 rounded">
                <li class="breadcrumb-item">
                    <a href="{{ route('owner.dashboard') }}" class="text-decoration-none">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('owner.product-monitor.index') }}" class="text-decoration-none">Monitor Produk</a>
                </li>
                <li class="breadcrumb-item active">{{ $product->name }}</li>
            </ol>
        </nav>

        <!-- Product Header Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-2">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 class="img-fluid rounded border" alt="{{ $product->name }}"
                                 style="max-height: 100px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center border"
                                 style="height: 100px; width: 100px;">
                                <i class="fas fa-box text-muted fa-2x"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <h4 class="fw-bold mb-2">{{ $product->name }}</h4>
                        <p class="text-muted mb-1">
                            <span class="badge bg-secondary">{{ $product->code }}</span>
                            {{ $product->category->name }} • {{ $product->unit->name }}
                        </p>
                        <p class="mb-0">{{ $product->description ?? 'Tidak ada deskripsi' }}</p>
                    </div>
                    <div class="col-md-2 text-end">
                        @if($product->stock_status === 'out_of_stock')
                            <span class="badge bg-danger fs-6">Stok Habis</span>
                        @elseif($product->stock_status === 'low_stock')
                            <span class="badge bg-warning fs-6">Stok Rendah</span>
                        @else
                            <span class="badge bg-success fs-6">Stok Normal</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="card-title mb-1">Stok Saat Ini</h6>
                                <h3 class="mb-0">{{ number_format($product->current_stock) }}</h3>
                                <small class="opacity-75">{{ $product->unit->symbol }}</small>
                            </div>
                            <div>
                                <i class="fas fa-boxes fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="card-title mb-1">Masuk Bulan Ini</h6>
                                <h3 class="mb-0">{{ number_format($monthlyStats['stock_in']) }}</h3>
                                <small class="opacity-75">{{ $product->unit->symbol }}</small>
                            </div>
                            <div>
                                <i class="fas fa-arrow-down fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="card-title mb-1">Keluar Bulan Ini</h6>
                                <h3 class="mb-0">{{ number_format($monthlyStats['stock_out']) }}</h3>
                                <small class="opacity-75">{{ $product->unit->symbol }}</small>
                            </div>
                            <div>
                                <i class="fas fa-arrow-up fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="card-title mb-1">Nilai Inventaris</h6>
                                <h3 class="mb-0">Rp {{ number_format($product->inventory_value, 0, ',', '.') }}</h3>
                                <small class="opacity-75">Current Value</small>
                            </div>
                            <div>
                                <i class="fas fa-money-bill-wave fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <div class="col-md-8">
                <!-- Stock Trend Chart -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-chart-line text-primary me-2"></i>
                            Trend Stok 30 Hari Terakhir
                        </h6>
                    </div>
                    <div class="card-body">
                        <canvas id="stockTrendChart" height="100"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <!-- Product Info -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-info-circle text-info me-2"></i>
                            Informasi Produk
                        </h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted">Harga Beli:</td>
                                <td class="fw-semibold">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Harga Jual:</td>
                                <td class="fw-semibold">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Stok Minimum:</td>
                                <td class="fw-semibold">{{ number_format($product->minimum_stock) }} {{ $product->unit->symbol }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Status:</td>
                                <td class="fw-semibold">
                                    @if($product->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Movement History -->
        <div class="card shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0">
                    <i class="fas fa-history text-secondary me-2"></i>
                    Riwayat Pergerakan Stok
                </h6>
                <small class="text-muted">{{ $stockMovements->total() }} total transaksi</small>
            </div>
            <div class="card-body p-0">
                @if($stockMovements->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kode Transaksi</th>
                                    <th>Jenis</th>
                                    <th>Jumlah</th>
                                    <th>Stok Sebelum</th>
                                    <th>Stok Sesudah</th>
                                    <th>Supplier</th>
                                    <th>User</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stockMovements as $movement)
                                    <tr>
                                        <td>{{ $movement->transaction_date->format('d/m/Y') }}</td>
                                        <td>
                                            <code class="text-primary">{{ $movement->transaction_code }}</code>
                                        </td>
                                        <td>
                                            @if($movement->type === 'in')
                                                <span class="badge bg-success">Masuk</span>
                                            @else
                                                <span class="badge bg-danger">Keluar</span>
                                            @endif
                                        </td>
                                        <td class="fw-semibold">
                                            @if($movement->type === 'in')
                                                <span class="text-success">+{{ number_format($movement->quantity) }}</span>
                                            @else
                                                <span class="text-danger">-{{ number_format($movement->quantity) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($movement->stock_before) }}</td>
                                        <td>{{ number_format($movement->stock_after) }}</td>
                                        <td>{{ $movement->supplier->name ?? '-' }}</td>
                                        <td>{{ $movement->user->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-light">
                        {{ $stockMovements->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-history text-muted fa-3x mb-3"></i>
                        <p class="text-muted">Belum ada riwayat transaksi</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Stock Alerts -->
        @if($product->stockAlerts->count() > 0)
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-warning text-dark">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Peringatan Stok Terbaru
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($product->stockAlerts->take(5) as $alert)
                        <div class="d-flex align-items-center mb-2 pb-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="me-3">
                                @if($alert->type === 'out_of_stock')
                                    <i class="fas fa-times-circle text-danger"></i>
                                @else
                                    <i class="fas fa-exclamation-circle text-warning"></i>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-1">{{ $alert->message }}</p>
                                <small class="text-muted">{{ $alert->alert_date->diffForHumans() }}</small>
                            </div>
                            @if(!$alert->is_read)
                                <span class="badge bg-danger">Baru</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Stock Trend Chart
        const stockTrendData = @json($stockTrend);
        const ctx = document.getElementById('stockTrendChart').getContext('2d');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: stockTrendData.map(item => {
                    const date = new Date(item.date);
                    return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
                }),
                datasets: [{
                    label: 'Stok',
                    data: stockTrendData.map(item => item.stock_level),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</x-admin-layout>
