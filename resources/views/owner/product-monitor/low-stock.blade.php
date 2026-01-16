<x-admin-layout title="Produk Stok Rendah">
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
                <li class="breadcrumb-item active">Stok Rendah</li>
            </ol>
        </nav>

        <!-- Alert Card -->
        <div class="card bg-warning text-dark shadow-sm mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="fw-bold mb-1">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Peringatan Stok Rendah
                        </h5>
                        <p class="mb-0">
                            Terdapat <strong>{{ $totalLowStock }}</strong> produk dengan stok di bawah minimum.
                            Segera lakukan pemesanan untuk menghindari stok habis.
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <h2 class="fw-bold mb-0">{{ $totalLowStock }}</h2>
                        <small>Produk perlu perhatian</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('owner.product-monitor.low-stock') }}" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Filter Kategori</label>
                        <select name="category_id" class="form-select">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                        {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                        <a href="{{ route('owner.product-monitor.low-stock') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Low Stock Products -->
        <div class="card shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0">
                    <i class="fas fa-box-open text-warning me-2"></i>
                    Daftar Produk Stok Rendah
                </h6>
                <span class="badge bg-warning text-dark">{{ $lowStockProducts->total() }} produk</span>
            </div>
            <div class="card-body p-0">
                @if($lowStockProducts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="60">#</th>
                                    <th>Produk</th>
                                    <th>Kategori</th>
                                    <th>Stok Saat Ini</th>
                                    <th>Stok Minimum</th>
                                    <th>Persentase</th>
                                    <th>Status Urgency</th>
                                    <th width="120">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStockProducts as $product)
                                    <tr>
                                        <td>{{ $loop->iteration + ($lowStockProducts->currentPage() - 1) * $lowStockProducts->perPage() }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($product->image)
                                                    <img src="{{ asset('storage/' . $product->image) }}"
                                                         class="rounded me-3" style="width: 40px; height: 40px; object-fit: cover;"
                                                         alt="{{ $product->name }}">
                                                @else
                                                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                                         style="width: 40px; height: 40px;">
                                                        <i class="fas fa-box text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-semibold">{{ $product->name }}</div>
                                                    <small class="text-muted">{{ $product->code }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $product->category->name }}</span>
                                        </td>
                                        <td class="fw-semibold text-warning">
                                            {{ number_format($product->current_stock) }} {{ $product->unit->symbol }}
                                        </td>
                                        <td>
                                            {{ number_format($product->minimum_stock) }} {{ $product->unit->symbol }}
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress me-2" style="width: 80px; height: 20px;">
                                                    <div class="progress-bar
                                                        @if($product->stock_percentage < 25) bg-danger
                                                        @elseif($product->stock_percentage < 50) bg-warning
                                                        @else bg-success
                                                        @endif"
                                                        style="width: {{ min($product->stock_percentage, 100) }}%">
                                                    </div>
                                                </div>
                                                <small class="fw-semibold">{{ $product->stock_percentage }}%</small>
                                            </div>
                                        </td>
                                        <td>
                                            @if($product->stock_percentage < 25)
                                                <span class="badge bg-danger">Kritis</span>
                                            @elseif($product->stock_percentage < 50)
                                                <span class="badge bg-warning">Peringatan</span>
                                            @else
                                                <span class="badge bg-info">Perhatian</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('owner.product-monitor.show', $product) }}"
                                               class="btn btn-sm btn-outline-primary" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-light">
                        {{ $lowStockProducts->withQueryString()->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                        <h5 class="text-success">Tidak Ada Produk Stok Rendah</h5>
                        <p class="text-muted">Semua produk memiliki stok yang mencukupi</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
