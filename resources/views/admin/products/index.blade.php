<x-admin-layout title="Data Produk">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-box mr-2 text-primary"></i>Data Produk
            </h1>
            <div class="d-flex gap-2">
                {{-- <button type="button" class="btn btn-success shadow-sm" data-toggle="modal" data-target="#importModal">
                    <i class="fas fa-upload fa-sm text-white-50 mr-1"></i> Import
                </button> --}}
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary shadow-sm">
                    <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Tambah Produk
                </a>
            </div>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
                <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Produk</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total_products']) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-box fa-2x text-gray-300"></i>
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
                                    Produk Aktif</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['active_products']) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                    Stok Rendah</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['low_stock_count']) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Stok Habis</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['out_of_stock_count']) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts for Expired/Near Expiry Products -->
        @if(isset($stats['expired_count']) && $stats['expired_count'] > 0)
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>Peringatan!</strong> Terdapat {{ $stats['expired_count'] }} produk yang sudah expired.
                <a href="{{ route('admin.products.index', ['expiry_status' => 'expired']) }}" class="alert-link">Lihat detail</a>
                <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(isset($stats['near_expiry_count']) && $stats['near_expiry_count'] > 0)
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-clock mr-2"></i>
                <strong>Perhatian!</strong> Terdapat {{ $stats['near_expiry_count'] }} produk yang akan segera expired.
                <a href="{{ route('admin.products.index', ['expiry_status' => 'near_expiry']) }}" class="alert-link">Lihat detail</a>
                <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Filter & Search Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Filter & Pencarian</h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.products.index') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="search">Pencarian</label>
                                <input type="text" class="form-control" id="search" name="search"
                                       value="{{ request('search') }}"
                                       placeholder="Cari nama, kode, atau deskripsi...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="category_id">Kategori</label>
                                <select class="form-control" id="category_id" name="category_id">
                                    <option value="">Semua Kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="unit">Satuan</label>
                                <select class="form-control" id="unit" name="unit">
                                    <option value="">Semua Satuan</option>
                                    @foreach ($units as $key => $value)
                                        <option value="{{ $value }}"
                                            {{ request('unit') == $value ? 'selected' : '' }}>
                                            {{ $key }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="stock_status">Status Stok</label>
                                <select class="form-control" id="stock_status" name="stock_status">
                                    <option value="">Semua Status</option>
                                    <option value="normal" {{ request('stock_status') == 'normal' ? 'selected' : '' }}>
                                        Normal</option>
                                    <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>
                                        Stok Rendah</option>
                                    <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>
                                        Stok Habis</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="is_active">Status Aktif</label>
                                <select class="form-control" id="is_active" name="is_active">
                                    <option value="">Semua Status</option>
                                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <label style="opacity: 0;">Filter</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex align-items-center gap-2">
                                <label class="mb-0">Urutkan:</label>
                                <select name="sort_by" class="form-control" style="width: auto; display: inline-block;">
                                    <option value="">Default</option>
                                    <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Nama</option>
                                    <option value="code" {{ request('sort_by') == 'code' ? 'selected' : '' }}>Kode</option>
                                    <option value="current_stock" {{ request('sort_by') == 'current_stock' ? 'selected' : '' }}>Stok</option>
                                    <option value="category_id" {{ request('sort_by') == 'category_id' ? 'selected' : '' }}>Kategori</option>
                                    <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Tanggal Dibuat</option>
                                </select>
                                <select name="sort_direction" class="form-control" style="width: auto; display: inline-block;">
                                    <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}>A-Z</option>
                                    <option value="desc" {{ request('sort_direction') == 'desc' ? 'selected' : '' }}>Z-A</option>
                                </select>
                                @if(request()->hasAny(['search', 'category_id', 'unit', 'stock_status', 'is_active', 'sort_by']))
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-sync"></i> Reset
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Products Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    Daftar Produk ({{ $products->total() }} data)
                </h6>
            </div>
            <div class="card-body">
                @if ($products->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="20%">Kode/Nama</th>
                                    <th width="12%">Kategori</th>
                                    <th width="10%">Satuan</th>
                                    <th width="12%">Stok</th>
                                    <th width="10%">Status Stok</th>
                                    <th width="8%">Status</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                                        <td>
                                            <div class="font-weight-bold">{{ $product->code }}</div>
                                            <div class="text-muted small">{{ $product->name }}</div>
                                            @if($product->description)
                                                <div class="text-muted small">{{ Str::limit($product->description, 50) }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-secondary">{{ $product->category->name }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $product->unit }}</span>
                                        </td>
                                        <td>
                                            <div class="font-weight-bold">{{ number_format($product->current_stock) }}</div>
                                            <div class="text-muted small">Min: {{ number_format($product->minimum_stock) }}</div>
                                        </td>
                                        <td>
                                            @if ($product->current_stock <= 0)
                                                <span class="badge badge-danger">Habis</span>
                                            @elseif($product->current_stock <= $product->minimum_stock)
                                                <span class="badge badge-warning">Rendah</span>
                                            @else
                                                <span class="badge badge-success">Normal</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($product->is_active)
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-secondary">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.products.show', $product) }}"
                                                    class="btn btn-sm btn-info" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.products.edit', $product) }}"
                                                    class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" title="Hapus"
                                                    onclick="confirmDelete('{{ $product->id }}', '{{ $product->name }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Menampilkan {{ $products->firstItem() }} - {{ $products->lastItem() }}
                            dari {{ $products->total() }} data
                        </div>
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-box fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada produk</h5>
                        <p class="text-muted">Silakan tambah produk pertama Anda</p>
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Produk
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="file">File Excel/CSV</label>
                            <input type="file" class="form-control-file" id="file" name="file"
                                accept=".xlsx,.xls,.csv" required>
                            <small class="form-text text-muted">
                                Format yang didukung: .xlsx, .xls, .csv (Max: 5MB)
                            </small>
                        </div>
                        <div class="alert alert-info">
                            <small>
                                <strong>Format File:</strong><br>
                                Kolom yang diperlukan: name, code, category_id, unit, current_stock, minimum_stock, description, is_active
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload mr-1"></i>Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Forms (Hidden) -->
    @foreach ($products as $product)
        <form id="delete-form-{{ $product->id }}" action="{{ route('admin.products.destroy', $product) }}"
            method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @endforeach

    @push('scripts')
        <script>
            function confirmDelete(id, name) {
                if (confirm(`Apakah Anda yakin ingin menghapus produk "${name}"?`)) {
                    document.getElementById('delete-form-' + id).submit();
                }
            }

            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert-success, .alert-info');
                alerts.forEach(alert => {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000);
        </script>
    @endpush
</x-admin-layout>
