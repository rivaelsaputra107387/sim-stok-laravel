<x-admin-layout title="Laporan Ringkasan Bulanan">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-chart-line"></i> Laporan Ringkasan Bulanan
            </h1>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary btn-sm" onclick="exportReport('pdf')">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </button>
                <button type="button" class="btn btn-success btn-sm" onclick="exportReport('excel')">
                    <i class="fas fa-file-excel"></i> Export Excel
                </button>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.reports.monthly-summary') }}" id="filterForm">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="year" class="form-label">Tahun</label>
                            <select name="year" id="year" class="form-control">
                                @for($y = 2020; $y <= date('Y'); $y++)
                                    <option value="{{ $y }}" {{ $filters['year'] == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="month" class="form-label">Bulan</label>
                            <select name="month" id="month" class="form-control">
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ $filters['month'] == $m ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Tampilkan
                                </button>
                                <a href="{{ route('admin.reports.monthly-summary') }}" class="btn btn-secondary">
                                    <i class="fas fa-undo"></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Period Info -->
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            <strong>Periode Laporan:</strong> {{ $period['month_name'] }} {{ $period['year'] }}
            <small class="float-right">
                <i class="fas fa-clock"></i> Generated: {{ $generated_at->format('d/m/Y H:i:s') }}
            </small>
        </div>

        <!-- Summary Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Transaksi
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($summary['total_transactions']) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exchange-alt fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Barang Masuk
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($summary['stock_in_count']) }}
                                </div>
                                <div class="text-xs text-muted">
                                    Rp {{ number_format($summary['stock_in_value'], 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-arrow-down fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Barang Keluar
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($summary['stock_out_count']) }}
                                </div>
                                <div class="text-xs text-muted">
                                    Rp {{ number_format($summary['stock_out_value'], 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-arrow-up fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-{{ $summary['net_value'] >= 0 ? 'success' : 'danger' }} shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-{{ $summary['net_value'] >= 0 ? 'success' : 'danger' }} text-uppercase mb-1">
                                    Net Value
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($summary['net_value'], 0, ',', '.') }}
                                </div>
                                <div class="text-xs text-muted">
                                    {{ $summary['net_value'] >= 0 ? 'Surplus' : 'Defisit' }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-{{ $summary['net_value'] >= 0 ? 'chart-line' : 'chart-line-down' }} fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <!-- Daily Transactions Chart -->
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Transaksi Harian</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                aria-labelledby="dropdownMenuLink">
                                <div class="dropdown-header">Opsi Chart:</div>
                                <a class="dropdown-item" href="#" onclick="toggleChartType('line')">Line Chart</a>
                                <a class="dropdown-item" href="#" onclick="toggleChartType('bar')">Bar Chart</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="dailyTransactionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category Breakdown -->
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Breakdown Kategori</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-pie pt-4 pb-2">
                            <canvas id="categoryChart"></canvas>
                        </div>
                        <div class="mt-4 text-center small">
                            @foreach($category_breakdown as $category => $data)
                            <span class="mr-2">
                                <i class="fas fa-circle text-{{ ['primary', 'success', 'info', 'warning', 'danger'][array_keys($category_breakdown->toArray())[$loop->index] % 5] }}"></i>
                                {{ $category }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daily Data Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Detail Transaksi Harian</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dailyDataTable" width="100%">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Barang Masuk</th>
                                <th>Nilai Masuk</th>
                                <th>Barang Keluar</th>
                                <th>Nilai Keluar</th>
                                <th>Net Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($daily_data as $day)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($day['date'])->format('d/m/Y') }}</td>
                                <td>{{ number_format($day['stock_in_qty']) }}</td>
                                <td>Rp {{ number_format($day['stock_in'], 0, ',', '.') }}</td>
                                <td>{{ number_format($day['stock_out_qty']) }}</td>
                                <td>Rp {{ number_format($day['stock_out'], 0, ',', '.') }}</td>
                                <td class="text-{{ ($day['stock_in'] - $day['stock_out']) >= 0 ? 'success' : 'danger' }}">
                                    Rp {{ number_format($day['stock_in'] - $day['stock_out'], 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Category Breakdown Table -->
        @if($category_breakdown->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Breakdown per Kategori</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="categoryBreakdownTable" width="100%">
                        <thead>
                            <tr>
                                <th>Kategori</th>
                                <th>Total Transaksi</th>
                                <th>Nilai Masuk</th>
                                <th>Nilai Keluar</th>
                                <th>Total Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($category_breakdown as $category => $data)
                            <tr>
                                <td>{{ $category }}</td>
                                <td>{{ number_format($data['transactions_count']) }}</td>
                                <td>Rp {{ number_format($data['stock_in_value'], 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($data['stock_out_value'], 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($data['total_value'], 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    @push('scripts')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTables
            $('#dailyDataTable').DataTable({
                "pageLength": 31,
                "order": [[ 0, "asc" ]],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
                }
            });

            $('#categoryBreakdownTable').DataTable({
                "pageLength": 25,
                "order": [[ 4, "desc" ]],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
                }
            });

            // Initialize Charts
            initDailyTransactionChart();
            initCategoryChart();
        });

        let dailyChart;
        let categoryChart;

        function initDailyTransactionChart() {
            const ctx = document.getElementById('dailyTransactionChart');
            const dailyData = @json($daily_data);

            dailyChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: dailyData.map(item => item.day),
                    datasets: [{
                        label: 'Barang Masuk',
                        data: dailyData.map(item => item.stock_in),
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.1
                    }, {
                        label: 'Barang Keluar',
                        data: dailyData.map(item => item.stock_out),
                        borderColor: 'rgb(255, 99, 132)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Pergerakan Nilai Transaksi Harian'
                        },
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        }

        function initCategoryChart() {
            const ctx = document.getElementById('categoryChart');
            const categoryData = @json($category_breakdown);

            const labels = Object.keys(categoryData);
            const data = Object.values(categoryData).map(item => item.total_value);
            const colors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'];

            categoryChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: colors.slice(0, labels.length),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });
        }

        function toggleChartType(type) {
            dailyChart.destroy();

            const ctx = document.getElementById('dailyTransactionChart');
            const dailyData = @json($daily_data);

            dailyChart = new Chart(ctx, {
                type: type,
                data: {
                    labels: dailyData.map(item => item.day),
                    datasets: [{
                        label: 'Barang Masuk',
                        data: dailyData.map(item => item.stock_in),
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.1
                    }, {
                        label: 'Barang Keluar',
                        data: dailyData.map(item => item.stock_out),
                        borderColor: 'rgb(255, 99, 132)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Pergerakan Nilai Transaksi Harian'
                        },
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        }

        function exportReport(type) {
            const form = document.getElementById('filterForm');
            const formData = new FormData(form);
            formData.append('export_type', type);

            const params = new URLSearchParams(formData);
            window.open(`{{ route('admin.reports.monthly-summary') }}?${params.toString()}`, '_blank');
        }

        // Auto-submit form when filter changes
        document.getElementById('year').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });

        document.getElementById('month').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    </script>
    @endpush
</x-admin-layout>
