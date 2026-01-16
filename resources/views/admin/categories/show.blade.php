{{-- resources/views/admin/categories/show.blade.php --}}
<x-admin-layout title="Detail Kategori">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-eye"></i> Detail Kategori: {{ $category->name }}
            </h1>
            <div>
                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning btn-sm shadow-sm mr-2">
                    <i class="fas fa-edit fa-sm text-white-50"></i> Edit
                </a>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-sm shadow-sm">
                    <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Kategori</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
            </ol>
        </nav>

        <div class="row">
            <!-- Category Details Card -->
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-info-circle"></i> Informasi Kategori
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-4 font-weight-bold">Nama:</div>
                            <div class="col-sm-8">{{ $category->name }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4 font-weight-bold">Kode:</div>
                            <div class="col-sm-8">
                                <span class="badge badge-secondary">{{ $category->code }}</span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4 font-weight-bold">Status:</div>
                            <div class="col-sm-8">
                                @if($category->is_active)
                                    <span class="badge badge-success">
                                        <i class="fas fa-check"></i> Aktif
                                    </span>
                                @else
                                    <span class="badge badge-danger">
                                        <i class="fas fa-times"></i> Non-Aktif
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4 font-weight-bold">Dibuat:</div>
                            <div class="col-sm-8">
                                <small class="text-muted">
                                    {{ $category->created_at->format('d/m/Y H:i') }}
                                </small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4 font-weight-bold">Diperbarui:</div>
                            <div class="col-sm-8">
                                <small class="text-muted">
                                    {{ $category->updated_at->format('d/m/Y H:i') }}
                                </small>
                            </div>
                        </div>

                    

                        <!-- Action Buttons -->
                        <hr>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>

                            <form action="{{ route('admin.categories.toggle', $category) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm {{ $category->is_active ? 'btn-secondary' : 'btn-success' }}">
                                    @if($category->is_active)
                                        <i class="fas fa-pause"></i> Non-Aktifkan
                                    @else
                                        <i class="fas fa-play"></i> Aktifkan
                                    @endif
                                </button>
                            </form>

                            @if($productsStats['total'] == 0)
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="col-lg-8">
                <!-- Stats Row -->
                <div class="row mb-4">
                    <!-- Total Products -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Produk
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $productsStats['total'] }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-box fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Active Products -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Produk Aktif
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $productsStats['active'] }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Low Stock -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Stok Rendah
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $productsStats['low_stock'] }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Out of Stock -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-danger shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                            Stok Habis
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $productsStats['out_of_stock'] }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Stats Row for Expiry -->
                <div class="row mb-4">
                    <!-- Expired Products -->
                    <div class="col-xl-6 col-md-6 mb-4">
                        <div class="card border-left-danger shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                            Produk Kedaluwarsa
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $productsStats['expired'] }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar-times fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Near Expiry Products -->
                    <div class="col-xl-6 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Hampir Kedaluwarsa
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $productsStats['near_expiry'] }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products List -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-list"></i> Daftar Produk dalam Kategori
                        </h6>
                        @if($category->products->count() > 0)
                        <a href="{{ route('admin.products.index', ['category' => $category->id]) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye"></i> Lihat Semua
                        </a>
                        @endif
                    </div>
                    <div class="card-body">
                        @if($category->products->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama Produk</th>
                                        <th>Satuan</th>
                                        <th>Stok</th>
                                        <th>Tanggal Kedaluwarsa</th>
                                        <th>Harga Beli</th>
                                        <th>Harga Jual</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($category->products->take(10) as $product)
                                    <tr>
                                        <td>
                                            <span class="badge badge-secondary">{{ $product->code }}</span>
                                        </td>
                                        <td class="font-weight-bold">{{ $product->name }}</td>
                                        <td>
                                            <span class="badge badge-info">{{ $product->unit_text }}</span>
                                        </td>
                                        <td>
                                            @if($product->current_stock <= 0)
                                                <span class="badge badge-danger">
                                                    <i class="fas fa-times"></i> Habis
                                                </span>
                                            @elseif($product->current_stock <= $product->minimum_stock)
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-exclamation-triangle"></i> {{ $product->current_stock }}
                                                </span>
                                            @else
                                                <span class="badge badge-success">{{ $product->current_stock }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($product->expired_date)
                                                @if($product->is_expired)
                                                    <span class="badge badge-danger">
                                                        <i class="fas fa-times"></i> {{ $product->expired_date->format('d/m/Y') }}
                                                    </span>
                                                @elseif($product->is_near_expiry)
                                                    <span class="badge badge-warning">
                                                        <i class="fas fa-clock"></i> {{ $product->expired_date->format('d/m/Y') }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-success">{{ $product->expired_date->format('d/m/Y') }}</span>
                                                @endif
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                                        <td>
                                            @if($product->is_active)
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check"></i> Aktif
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">
                                                    <i class="fas fa-pause"></i> Non-Aktif
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.products.show', $product) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($category->products->count() > 10)
                        <div class="text-center mt-3">
                            <p class="text-muted">
                                Menampilkan 10 dari {{ $category->products->count() }} produk.
                                <a href="{{ route('admin.products.index', ['category' => $category->id]) }}">
                                    Lihat semua produk
                                </a>
                            </p>
                        </div>
                        @endif

                        @else
                        <div class="text-center py-4">
                            <i class="fas fa-box-open fa-3x text-gray-300 mb-3"></i>
                            <h5 class="text-gray-500">Belum ada produk dalam kategori ini</h5>
                            <p class="text-muted">Tambah produk baru untuk kategori {{ $category->name }}</p>
                            <a href="{{ route('admin.products.create', ['category' => $category->id]) }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Produk
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Confirmation for toggle status
        document.addEventListener('DOMContentLoaded', function() {
            const toggleForms = document.querySelectorAll('form[action*="toggle"]');

            toggleForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const isActive = {{ $category->is_active ? 'true' : 'false' }};
                    const action = isActive ? 'menonaktifkan' : 'mengaktifkan';

                    if (confirm(`Apakah Anda yakin ingin ${action} kategori ini?`)) {
                        this.submit();
                    }
                });
            });
        });
    </script>
    @endpush
</x-admin-layout>
