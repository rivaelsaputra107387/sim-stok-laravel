{{-- resources/views/admin/reports/index.blade.php --}}
<x-admin-layout title="Laporan Stok">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Laporan Manajemen Inventory</h1>
            <div class="d-flex">
                <!-- Export PDF Button -->
                <a href="{{ route('admin.reports.stock.pdf', request()->all()) }}" class="btn btn-danger shadow-sm mr-2"
                    target="_blank" title="Cetak/Download PDF">
                    <i class="fas fa-file-pdf fa-sm text-white-50 mr-1"></i>
                    Cetak PDF
                </a>

                <!-- Export Excel Button (Optional - untuk masa depan) -->
                <a href="#" class="btn btn-success shadow-sm mr-2 d-none" title="Export ke Excel">
                    <i class="fas fa-file-excel fa-sm text-white-50 mr-1"></i>
                    Export Excel
                </a>

                <!-- Print Button (untuk print langsung dari browser) -->
                {{-- <button type="button" class="btn btn-info shadow-sm" onclick="printReport()" title="Print Halaman">
                    <i class="fas fa-print fa-sm text-white-50 mr-1"></i>
                    Print
                </button> --}}
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>

            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.reports.index') }}" id="filterForm">
                    <div class="row">
                        <!-- Category Filter -->
                        <div class="col-md-3 mb-3">
                            <label for="category_id" class="form-label">Kategori</label>
                            <select name="category_id" id="category_id" class="form-control">
                                <option value="">Semua Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Stock Status Filter -->
                        <div class="col-md-3 mb-3">
                            <label for="stock_status" class="form-label">Status Stok</label>
                            <select name="stock_status" id="stock_status" class="form-control">
                                <option value="all" {{ request('stock_status', 'all') === 'all' ? 'selected' : '' }}>
                                    Semua Status
                                </option>
                                <option value="normal" {{ request('stock_status') === 'normal' ? 'selected' : '' }}>
                                    Stok Normal
                                </option>
                                <option value="low_stock"
                                    {{ request('stock_status') === 'low_stock' ? 'selected' : '' }}>
                                    Stok Menipis
                                </option>
                                <option value="out_of_stock"
                                    {{ request('stock_status') === 'out_of_stock' ? 'selected' : '' }}>
                                    Stok Habis
                                </option>
                            </select>
                        </div>

                        <!-- Unit Filter -->
                        <div class="col-md-3 mb-3">
                            <label for="unit" class="form-label">Satuan</label>
                            <select name="unit" id="unit" class="form-control">
                                <option value="">Semua Satuan</option>
                                <option value="krg" {{ request('unit') === 'krg' ? 'selected' : '' }}>Krg</option>
                                <option value="dus" {{ request('unit') === 'dus' ? 'selected' : '' }}>Dus</option>
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label">&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search fa-sm mr-1"></i> Filter
                                </button>
                                <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary ml-2">
                                    <i class="fas fa-redo fa-sm mr-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-xl-2 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Produk
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($summary['total_products']) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-boxes fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Tersedia</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($summary['products_in_stock']) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Menipis</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($summary['low_stock_count']) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Habis</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($summary['out_of_stock_count']) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Krg</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($summary['total_krg_stock']) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-weight fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-6 mb-4">
                <div class="card border-left-dark shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">Total Dus</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($summary['total_dus_stock']) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-cube fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Data Stok Barang</h6>
                <div class="d-flex">
                    <span class="badge badge-light mr-2">
                        Total: {{ number_format($products->count()) }} produk
                    </span>
                    {{-- <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-danger" onclick="exportPDF()">
                            <i class="fas fa-file-pdf fa-sm"></i>
                        </button>
                        <button type="button" class="btn btn-outline-info" onclick="printReport()">
                            <i class="fas fa-print fa-sm"></i>
                        </button>
                    </div> --}}
                </div>
            </div>
            <div class="card-body">
                @if ($products->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="stockTable">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="12%">Kode</th>
                                    <th width="25%">Nama Produk</th>
                                    <th width="15%">Kategori</th>
                                    <th width="8%">Satuan</th>
                                    <th width="10%">Stok</th>
                                    <th width="10%">Min. Stok</th>
                                    <th width="15%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $index => $product)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td class="text-center">
                                            <code>{{ $product->code }}</code>
                                        </td>
                                        <td>
                                            <strong>{{ $product->name }}</strong>
                                            @if ($product->description)
                                                <br><small
                                                    class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-secondary">{{ $product->category->name }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge {{ $product->unit === 'krg' ? 'badge-primary' : 'badge-info' }}">
                                                {{ strtoupper($product->unit) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <strong
                                                class="{{ $product->current_stock <= 0 ? 'text-danger' : ($product->is_low_stock ? 'text-warning' : 'text-success') }}">
                                                {{ number_format($product->current_stock) }}
                                            </strong>
                                        </td>
                                        <td class="text-center">{{ number_format($product->minimum_stock) }}</td>
                                        <td class="text-center">
                                            @if ($product->is_out_of_stock)
                                                <span class="badge badge-danger">
                                                    <i class="fas fa-times-circle mr-1"></i>
                                                    Habis
                                                </span>
                                            @elseif($product->is_low_stock)
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                                    Menipis
                                                </span>
                                            @else
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Normal
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="fas fa-search fa-3x text-muted"></i>
                        </div>
                        <h5 class="text-muted">Tidak ada data yang ditemukan</h5>
                        <p class="text-muted">Silakan ubah filter pencarian atau tambahkan produk baru.</p>
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus mr-1"></i> Tambah Produk
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- JavaScript Functions -->
    <script>
        // Function untuk export PDF dengan filter yang aktif
        function exportPDF() {
            const form = document.getElementById('filterForm');
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);

            // Buka PDF di tab baru
            const url = "{{ route('admin.reports.stock.pdf') }}" + '?' + params.toString();
            window.open(url, '_blank');
        }



        // Function untuk print halaman current
        function printReport() {
            window.print();
        }

        // Auto submit form ketika filter berubah (optional)
        document.addEventListener('DOMContentLoaded', function() {
            const filterSelects = document.querySelectorAll('#category_id, #stock_status, #unit');

            filterSelects.forEach(select => {
                select.addEventListener('change', function() {
                    // Auto submit form setelah 500ms (debounce)
                    setTimeout(() => {
                        document.getElementById('filterForm').submit();
                    }, 500);
                });
            });
        });


    </script>

    <!-- Print Styles -->
    <style media="print">
        .btn,
        .card-header,
        .no-print {
            display: none !important;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
        }

        .table {
            font-size: 12px;
        }

        .badge {
            border: 1px solid #000 !important;
            color: #000 !important;
            background: transparent !important;
        }
    </style>
</x-admin-layout>
