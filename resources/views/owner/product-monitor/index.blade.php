{{-- resources/views/owner/product-monitor/index.blade.php --}}
<x-admin-layout title="Monitoring Produk">
    <!-- Header Section -->
    <div class="container-fluid">
        <div class="card bg-gradient-primary text-white shadow mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="fw-semibold mb-1">
                            <i class="fas fa-chart-line me-2"></i>Monitoring Produk
                        </h4>
                        <p class="mb-0">Monitor dan analisis performa produk inventaris</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('owner.product-monitor.low-stock') }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-exclamation-triangle"></i> Stok Rendah
                            </a>
                            <a href="{{ route('owner.product-monitor.out-of-stock') }}" class="btn btn-danger btn-sm">
                                <i class="fas fa-times-circle"></i> Stok Habis
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Produk Aktif
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($stats['total_products']) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-boxes fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Stok Rendah
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($stats['low_stock_count']) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Stok Habis
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($stats['out_of_stock_count']) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Nilai Inventaris
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($stats['total_inventory_value'], 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search Section -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Filter & Pencarian</h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('owner.product-monitor.index') }}">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="search" class="form-label">Cari Produk</label>
                            <input type="text" class="form-control" name="search" id="search"
                                   placeholder="Nama atau kode produk..."
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="category_id" class="form-label">Kategori</label>
                            <select name="category_id" id="category_id" class="form-select">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="stock_status" class="form-label">Status Stok</label>
                            <select name="stock_status" id="stock_status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="normal" {{ request('stock_status') == 'normal' ? 'selected' : '' }}>Normal</option>
                                <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>Stok Rendah</option>
                                <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Stok Habis</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="sort_by" class="form-label">Urutkan</label>
                            <select name="sort_by" id="sort_by" class="form-select">
                                <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Nama</option>
                                <option value="stock_level" {{ request('sort_by') == 'stock_level' ? 'selected' : '' }}>Stok</option>
                                <option value="inventory_value" {{ request('sort_by') == 'inventory_value' ? 'selected' : '' }}>Nilai Inventaris</option>
                                <option value="activity" {{ request('sort_by') == 'activity' ? 'selected' : '' }}>Aktivitas</option>
                                <option value="category" {{ request('sort_by') == 'category' ? 'selected' : '' }}>Kategori</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="sort_order" class="form-label">Urutan</label>
                            <select name="sort_order" id="sort_order" class="form-select">
                                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>A-Z / Rendah-Tinggi</option>
                                <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Z-A / Tinggi-Rendah</option>
                            </select>
                        </div>
                        <div class="col-md-1 mb-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Products Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Produk</h6>
                <div class="d-flex gap-2">
                    <a href="{{ route('owner.product-monitor.top-products') }}" class="btn btn-info btn-sm">
                        <i class="fas fa-trophy"></i> Top Produk
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Kategori</th>
                                <th>Stok Saat Ini</th>
                                <th>Status Stok</th>
                                <th>Nilai Inventaris</th>
                                <th>Aktivitas (30 hari)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}"
                                                     alt="{{ $product->name }}"
                                                     class="rounded me-2"
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary rounded me-2 d-flex align-items-center justify-content-center"
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-box text-white"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold">{{ $product->name }}</div>
                                                <small class="text-muted">{{ $product->code }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $product->category->name }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ number_format($product->current_stock) }}</span>
                                        <small class="text-muted">{{ $product->unit->symbol }}</small>
                                        <br>
                                        <small class="text-muted">Min: {{ number_format($product->minimum_stock) }}</small>
                                    </td>
                                    <td>
                                        @if($product->current_stock <= 0)
                                            <span class="badge bg-danger">Stok Habis</span>
                                        @elseif($product->current_stock <= $product->minimum_stock)
                                            <span class="badge bg-warning">Stok Rendah</span>
                                        @else
                                            <span class="badge bg-success">Normal</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-bold">
                                            Rp {{ number_format($product->current_stock * $product->purchase_price, 0, ',', '.') }}
                                        </span>
                                        <br>
                                        <small class="text-muted">
                                            @ Rp {{ number_format($product->purchase_price, 0, ',', '.') }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div class="text-success">
                                                <i class="fas fa-arrow-up"></i> {{ number_format($product->total_stock_in ?? 0) }} Masuk
                                            </div>
                                            <div class="text-danger">
                                                <i class="fas fa-arrow-down"></i> {{ number_format($product->total_stock_out ?? 0) }} Keluar
                                            </div>
                                            <div class="text-info">
                                                <i class="fas fa-exchange-alt"></i> {{ number_format($product->total_transactions ?? 0) }} Transaksi
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('owner.product-monitor.show', $product) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-box fa-3x mb-3"></i>
                                            <h5>Tidak ada produk ditemukan</h5>
                                            <p>Silakan ubah filter pencarian Anda</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Menampilkan {{ $products->firstItem() }} - {{ $products->lastItem() }}
                            dari {{ $products->total() }} produk
                        </div>
                        <div>
                            {{ $products->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
