<x-admin-layout title="Dashboard Owner - Sistem Inventaris">
    @section('css')
        <style>
            .gradient-primary {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }

            .gradient-success {
                background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            }

            .gradient-warning {
                background: linear-gradient(135deg, #ffeaa7 0%, #fab1a0 100%);
            }

            .gradient-danger {
                background: linear-gradient(135deg, #fd79a8 0%, #e84393 100%);
            }

            .gradient-info {
                background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
            }

            .card-kpi {
                border-radius: 15px;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .card-kpi:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            }

            .kpi-icon {
                font-size: 2.5rem;
                opacity: 0.8;
            }

            .growth-positive {
                color: #28a745;
            }

            .growth-negative {
                color: #dc3545;
            }

            .chart-container {
                position: relative;
                height: 400px;
            }

            .alert-item {
                border-left: 4px solid #dc3545;
                background: #fff5f5;
            }

            .activity-item {
                border-left: 3px solid #007bff;
                padding-left: 15px;
            }

            .top-item {
                background: linear-gradient(45deg, #f8f9fa, #ffffff);
                border: 1px solid #e9ecef;
                border-radius: 10px;
            }
        </style>
    @endsection

    <!-- Welcome Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-tachometer-alt text-primary"></i>
                Dashboard Owner
            </h1>
            <p class="text-muted mb-0">Selamat datang, {{ auth()->user()->name }}! Pantau performa bisnis Anda</p>
        </div>
        <div class="text-right">
            <p class="mb-0 text-gray-800 font-weight-bold">{{ now()->format('l, d F Y') }}</p>
            <small class="text-muted">{{ now()->format('H:i') }} WIB</small>
        </div>
    </div>

    <!-- KPI Cards Row -->
    <div class="row mb-4">
        <!-- Total Products -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm card-kpi gradient-primary text-white">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Produk</div>
                            <div class="h5 mb-0 font-weight-bold">
                                {{ number_format($kpiData['total_products']['value']) }}</div>
                            @if ($kpiData['total_products']['growth'] != 0)
                                <small class="mt-1 d-block">
                                    <i
                                        class="fas fa-{{ $kpiData['total_products']['growth'] > 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                    {{ abs($kpiData['total_products']['growth']) }}% dari bulan lalu
                                </small>
                            @endif
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes kpi-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Value -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm card-kpi gradient-success text-white">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Nilai Inventaris</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $kpiData['inventory_value']['formatted'] }}</div>
                            <small class="mt-1 d-block">Total aset inventaris</small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign kpi-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Transactions -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm card-kpi gradient-info text-white">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Transaksi Bulan Ini</div>
                            <div class="h5 mb-0 font-weight-bold">
                                {{ number_format($kpiData['monthly_transactions']['value']) }}</div>
                            @if ($kpiData['monthly_transactions']['growth'] != 0)
                                <small class="mt-1 d-block">
                                    <i
                                        class="fas fa-{{ $kpiData['monthly_transactions']['growth'] > 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                    {{ abs($kpiData['monthly_transactions']['growth']) }}% dari bulan lalu
                                </small>
                            @endif
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exchange-alt kpi-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Alerts -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm card-kpi gradient-warning text-white">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Peringatan Stok</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $kpiData['stock_alerts']['total'] }}</div>
                            <small class="mt-1 d-block">
                                {{ $kpiData['stock_alerts']['critical'] }} kritis
                            </small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle kpi-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Status Overview -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie"></i> Status Stok Produk
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-success text-white rounded mb-3">
                                <h4 class="font-weight-bold">{{ $kpiData['stock_status']['normal'] }}</h4>
                                <p class="mb-0">Stok Normal</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-warning text-white rounded mb-3">
                                <h4 class="font-weight-bold">{{ $kpiData['stock_status']['low_stock'] }}</h4>
                                <p class="mb-0">Stok Rendah</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-danger text-white rounded mb-3">
                                <h4 class="font-weight-bold">{{ $kpiData['stock_status']['out_of_stock'] }}</h4>
                                <p class="mb-0">Stok Habis</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Stock Movement Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line"></i> Pergerakan Stok (6 Bulan Terakhir)
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="stockMovementChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Distribution -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-doughnut"></i> Distribusi Kategori
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Business Overview Row -->
    <div class="row mb-4">
        <!-- Top Categories -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-tags"></i> Top 5 Kategori
                    </h6>
                </div>
                <div class="card-body">
                    @foreach ($businessOverview['top_categories'] as $category)
                        <div class="top-item p-3 mb-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-0 font-weight-bold">{{ $category->name }}</h6>
                                    <small class="text-muted">Kategori Produk</small>
                                </div>
                                <div class="text-right">
                                    <span class="badge badge-primary badge-pill">{{ $category->products_count }}
                                        produk</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Top Suppliers -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-truck"></i> Top 5 Supplier
                    </h6>
                </div>
                <div class="card-body">
                    @foreach ($businessOverview['top_suppliers'] as $supplier)
                        <div class="top-item p-3 mb-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-0 font-weight-bold">{{ $supplier->name }}</h6>
                                    <small class="text-muted">Total Transaksi</small>
                                </div>
                                <div class="text-right">
                                    <span class="text-success font-weight-bold">Rp
                                        {{ number_format($supplier->total_value, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts and Activities Row -->
    <div class="row mb-4">
        <!-- Critical Alerts -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-danger">
                        <i class="fas fa-exclamation-circle"></i> Peringatan Kritis
                    </h6>
                    <span class="badge badge-danger">{{ $criticalAlerts->count() }}</span>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @forelse($criticalAlerts as $alert)
                        <div class="alert-item p-3 mb-2 rounded">
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <i class="fas fa-exclamation-triangle text-danger"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 font-weight-bold">{{ $alert->product->name }}</h6>
                                    <p class="mb-0 text-muted small">{{ $alert->message }}</p>
                                    <small class="text-muted">{{ $alert->alert_date->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-check-circle fa-3x mb-3"></i>
                            <p>Tidak ada peringatan kritis</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history"></i> Aktivitas Terbaru
                    </h6>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @forelse($recentActivities as $activity)
                        <div class="activity-item mb-3 pb-3 border-bottom">
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    @if ($activity->type == 'in')
                                        <i class="fas fa-arrow-down text-success"></i>
                                    @else
                                        <i class="fas fa-arrow-up text-danger"></i>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $activity->product->name }}</h6>
                                    <p class="mb-0 text-muted small">
                                        {{ $activity->type == 'in' ? 'Barang Masuk' : 'Barang Keluar' }} -
                                        {{ $activity->quantity }} {{ $activity->product->unit->symbol }}
                                    </p>
                                    <small class="text-muted">
                                        {{ $activity->user->name }} • {{ $activity->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                <div class="text-right">
                                    <span class="badge badge-{{ $activity->type == 'in' ? 'success' : 'warning' }}">
                                        {{ $activity->transaction_code }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>Belum ada aktivitas</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-tachometer-alt"></i> Ringkasan Cepat
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="border-right">
                                <h4 class="font-weight-bold text-primary">
                                    {{ number_format($kpiData['stock_movement']['stock_in']) }}</h4>
                                <p class="text-muted mb-0">Stok Masuk (Bulan Ini)</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="border-right">
                                <h4 class="font-weight-bold text-warning">
                                    {{ number_format($kpiData['stock_movement']['stock_out']) }}</h4>
                                <p class="text-muted mb-0">Stok Keluar (Bulan Ini)</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="border-right">
                                <h4
                                    class="font-weight-bold {{ $kpiData['stock_movement']['balance'] >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($kpiData['stock_movement']['balance']) }}
                                </h4>
                                <p class="text-muted mb-0">Balance Stok</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <h4 class="font-weight-bold text-info">{{ $businessOverview['user_activity']->count() }}
                            </h4>
                            <p class="text-muted mb-0">User Aktif (Bulan Ini)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Stock Movement Chart
                const stockMovementCtx = document.getElementById('stockMovementChart').getContext('2d');
                const stockMovementChart = new Chart(stockMovementCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($chartData['stock_movement']->pluck('month')) !!},
                        datasets: [{
                            label: 'Stok Masuk',
                            data: {!! json_encode($chartData['stock_movement']->pluck('stock_in')) !!},
                            borderColor: 'rgb(75, 192, 192)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            tension: 0.1
                        }, {
                            label: 'Stok Keluar',
                            data: {!! json_encode($chartData['stock_movement']->pluck('stock_out')) !!},
                            borderColor: 'rgb(255, 99, 132)',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            tension: 0.1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                // Category Distribution Chart
                const categoryCtx = document.getElementById('categoryChart').getContext('2d');
                const categoryChart = new Chart(categoryCtx, {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($chartData['category_distribution']->pluck('name')) !!},
                        datasets: [{
                            data: {!! json_encode($chartData['category_distribution']->pluck('value')) !!},
                            backgroundColor: [
                                '#FF6384',
                                '#36A2EB',
                                '#FFCE56',
                                '#4BC0C0',
                                '#9966FF',
                                '#FF9F40'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });

                // Auto-refresh alerts every 5 minutes
                setInterval(function() {
                    location.reload();
                }, 300000);
            });
        </script>
    @endpush
</x-admin-layout>
