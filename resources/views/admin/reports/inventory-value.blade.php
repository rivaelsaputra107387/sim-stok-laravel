<!-- resources/views/admin/reports/inventory-value.blade.php -->
<x-admin-layout title="Laporan Nilai Inventaris">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-chart-line mr-2"></i>Laporan Nilai Inventaris
            </h1>
            <div class="btn-group">
                <button type="button" class="btn btn-primary btn-sm" onclick="exportReport('pdf')">
                    <i class="fas fa-file-pdf mr-1"></i> Export PDF
                </button>
                <button type="button" class="btn btn-success btn-sm" onclick="exportReport('excel')">
                    <i class="fas fa-file-excel mr-1"></i> Export Excel
                </button>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-filter mr-1"></i>Filter Laporan
                </h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.reports.inventory-value') }}" id="filterForm">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="category_id">Kategori</label>
                                <select name="category_id" id="category_id" class="form-control">
                                    <option value="">Semua Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="group_by">Kelompokkan Berdasarkan</label>
                                <select name="group_by" id="group_by" class="form-control">
                                    <option value="">Tidak Dikelompokkan</option>
                                    <option value="category" {{ request('group_by') == 'category' ? 'selected' : '' }}>
                                        Kategori
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div class="d-block">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search mr-1"></i>Filter
                                    </button>
                                    <a href="{{ route('admin.reports.inventory-value') }}" class="btn btn-secondary">
                                        <i class="fas fa-times mr-1"></i>Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Produk
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($summary['total_products']) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-boxes fa-2x text-gray-300"></i>
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
                                    Total Kuantitas
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($summary['total_quantity']) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-cubes fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Total Nilai Inventaris
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($summary['total_value'], 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
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
                                    Rata-rata Nilai/Produk
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($summary['average_value_per_product'], 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calculator fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Products Chart -->
        @if($top_products->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-trophy mr-1"></i>Top 10 Produk Tertinggi Nilai
                        </h6>
                    </div>
                    <div class="card-body">
                        <canvas id="topProductsChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Grouped Data -->
        @if(request('group_by') && $grouped_data->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-layer-group mr-1"></i>Data Berdasarkan {{ ucfirst(request('group_by')) }}
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>{{ ucfirst(request('group_by')) }}</th>
                                <th>Jumlah Produk</th>
                                <th>Total Kuantitas</th>
                                <th>Total Nilai</th>
                                <th>Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($grouped_data as $group_name => $group_data)
                            <tr>
                                <td class="font-weight-bold">{{ $group_name }}</td>
                                <td>{{ number_format($group_data['product_count']) }}</td>
                                <td>{{ number_format($group_data['total_quantity']) }}</td>
                                <td>Rp {{ number_format($group_data['total_value'], 0, ',', '.') }}</td>
                                <td>
                                    <div class="progress">
                                        @php
                                            $percentage = $summary['total_value'] > 0 ? ($group_data['total_value'] / $summary['total_value']) * 100 : 0;
                                        @endphp
                                        <div class="progress-bar bg-primary" role="progressbar"
                                             style="width: {{ $percentage }}%">
                                            {{ number_format($percentage, 1) }}%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Detail Products Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-list mr-1"></i>Detail Produk & Nilai Inventaris
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Satuan</th>
                                <th>Stok</th>
                                <th>Harga Beli</th>
                                <th>Nilai Inventaris</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            <tr>
                                <td>{{ $product->code }}</td>
                                <td class="font-weight-bold">{{ $product->name }}</td>
                                <td>
                                    <span class="badge badge-secondary">{{ $product->category->name }}</span>
                                </td>
                                <td>{{ $product->unit->name }}</td>
                                <td>
                                    <span class="badge badge-{{ $product->current_stock > $product->minimum_stock ? 'success' : ($product->current_stock > 0 ? 'warning' : 'danger') }}">
                                        {{ number_format($product->current_stock) }}
                                    </span>
                                </td>
                                <td>Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                                <td class="font-weight-bold text-primary">
                                    Rp {{ number_format($product->inventory_value, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if($product->current_stock <= 0)
                                        <span class="badge badge-danger">Habis</span>
                                    @elseif($product->current_stock <= $product->minimum_stock)
                                        <span class="badge badge-warning">Stok Rendah</span>
                                    @else
                                        <span class="badge badge-success">Normal</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>Tidak ada data produk dengan stok tersedia</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Report Info -->
        <div class="card border-left-info shadow mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Informasi Laporan
                        </div>
                        <div class="small text-gray-500">
                            Laporan ini menampilkan nilai inventaris berdasarkan stok tersedia dan harga beli produk.
                            Digenerate pada: {{ $generated_at->format('d/m/Y H:i:s') }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-info-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <!-- Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

    <script>
        // Top Products Chart
        @if($top_products->count() > 0)
        const topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
        const topProductsChart = new Chart(topProductsCtx, {
            type: 'bar',
            data: {
                labels: [
                    @foreach($top_products as $product)
                        '{{ $product->name }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Nilai Inventaris (Rp)',
                    data: [
                        @foreach($top_products as $product)
                            {{ $product->inventory_value }},
                        @endforeach
                    ],
                    backgroundColor: 'rgba(78, 115, 223, 0.8)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Nilai: Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
        @endif

        // Export functions
        function exportReport(type) {
            const form = document.getElementById('filterForm');
            const exportInput = document.createElement('input');
            exportInput.type = 'hidden';
            exportInput.name = 'export_type';
            exportInput.value = type;
            form.appendChild(exportInput);
            form.submit();
            form.removeChild(exportInput);
        }

        // Auto submit form on filter change
        document.getElementById('category_id').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });

        document.getElementById('group_by').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    </script>
    @endpush
</x-admin-layout>
