<x-admin-layout title="Produk Stok Habis">
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
                <li class="breadcrumb-item active">Stok Habis</li>
            </ol>
        </nav>

        <!-- Alert Card -->
        <div class="card bg-danger text-white shadow-sm mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="fw-bold mb-1">
                            <i class="fas fa-times-circle me-2"></i>
                            Peringatan Stok Habis
                        </h5>
                        <p class="mb-0">
                            Terdapat <strong>{{ $totalOutOfStock }}</strong> produk yang stoknya habis.
                            Produk ini tidak dapat dijual sampai stok diisi kembali.
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <h2 class="fw-bold mb-0">{{ $totalOutOfStock }}</h2>
                        <small>Produk stok habis</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('owner.product-monitor.out-of-stock') }}" class="row g-3">
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
                        <a href="{{ route('owner.product-monitor.out-of-stock') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Out of Stock Products -->
        <div class="card shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0">
                    <i class="fas fa-ban text-danger me-2"></i>
                    Daftar Produk Stok Habis
                </h6>
                <span class="badge bg-danger">{{ $outOfStockProducts->total() }} produk</span>
            </div>
            <div class="card-body p-0">
                @if($outOfStockProducts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="60">#</th>
                                    <th>Produk</th>
                                    <th>Kategori</th>
                                    <th>Stok Minimum</th>
                                    <th>Transaksi Terakhir</th>
                                    <th>Hari Kosong</th>
                                    <th>Status</th>
                                    <th width="120">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($outOfStockProducts as $product)
                                    <tr>
                                        <td>{{ $loop->iteration + ($outOfStockProducts->currentPage() - 1) * $outOfStockProducts->perPage() }}</td>
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
                                        <td>
                                            {{ number_format($product->minimum_stock) }} {{ $product->unit->symbol }}
                                        </td>
                                        <td>
                                            @if($product->last_transaction_date)
                                                {{ \Carbon\Carbon::parse($product->last_transaction_date)->format('d/m/Y') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($product->days_out_of_stock !== null)
                                                <span class="badge
                                                    @if($product->days_out_of_stock > 30) bg-danger
                                                    @elseif($product->days_out_of_stock > 14) bg-warning
                                                    @else bg-info
                                                    @endif">
                                                    {{ $product->days_out_of_stock }} hari
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-danger">Stok Habis</span>
                                        </td>
                                        <td>
