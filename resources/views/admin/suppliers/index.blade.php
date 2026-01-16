<x-admin-layout title="Supplier">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-truck mr-2 text-primary"></i>Data Supplier
            </h1>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary shadow-sm">
                    <i class="fas fa-plus-circle fa-sm text-white-50 mr-1"></i>Tambah Supplier
                </a>
            </div>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
                <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Filter & Search Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-filter mr-2"></i>Filter & Pencarian
                </h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.suppliers.index') }}">
                    <div class="row">
                        <!-- Search -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="search">Pencarian</label>
                                <input type="text" class="form-control" id="search" name="search"
                                       value="{{ request('search') }}"
                                       placeholder="Nama, kode, email, telepon, kontak...">
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="">Semua Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                                        Aktif
                                    </option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                        Nonaktif
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Sort By -->
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="sort_by">Urutkan</label>
                                <select class="form-control" id="sort_by" name="sort_by">
                                    <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>
                                        Nama
                                    </option>
                                    <option value="code" {{ request('sort_by') == 'code' ? 'selected' : '' }}>
                                        Kode
                                    </option>
                                    <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>
                                        Tanggal Dibuat
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Sort Order -->
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="sort_order">Urutan</label>
                                <select class="form-control" id="sort_order" name="sort_order">
                                    <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>
                                        A-Z / Terlama
                                    </option>
                                    <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>
                                        Z-A / Terbaru
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="col-md-2">
                            <label class="d-block">&nbsp;</label>
                            <button type="submit" class="btn btn-info btn-block">
                                <i class="fas fa-search"></i> Filter
                            </button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary">
                                <i class="fas fa-sync"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Suppliers Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    Data Supplier ({{ $suppliers->total() }} supplier)
                </h6>
            </div>
            <div class="card-body">
                @if ($suppliers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="10%">Kode</th>
                                    <th width="20%">Nama Supplier</th>
                                    <th width="15%">Kontak Person</th>
                                    <th width="12%">Telepon</th>
                                    <th width="15%">Email</th>
                                    <th width="8%">Transaksi</th>
                                    <th width="8%">Status</th>
                                    <th width="7%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($suppliers as $index => $supplier)
                                    <tr>
                                        <td class="text-center">
                                            {{ $suppliers->firstItem() + $index }}
                                        </td>
                                        <td>
                                            <span class="badge badge-secondary">
                                                {{ $supplier->code }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="font-weight-bold">{{ $supplier->name }}</div>
                                            @if($supplier->address)
                                                <small class="text-muted">
                                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                                    {{ Str::limit($supplier->address, 30) }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($supplier->contact_person)
                                                <i class="fas fa-user mr-1 text-muted"></i>
                                                {{ $supplier->contact_person }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($supplier->phone)
                                                <i class="fas fa-phone mr-1 text-muted"></i>
                                                {{ $supplier->phone }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($supplier->email)
                                                <i class="fas fa-envelope mr-1 text-muted"></i>
                                                <a href="mailto:{{ $supplier->email }}">{{ $supplier->email }}</a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-info">
                                                {{ $supplier->stock_transactions_count ?? 0 }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($supplier->is_active)
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check-circle mr-1"></i>Aktif
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">
                                                    <i class="fas fa-times-circle mr-1"></i>Nonaktif
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.suppliers.show', $supplier) }}"
                                                    class="btn btn-sm btn-info" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.suppliers.edit', $supplier) }}"
                                                    class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                {{-- <form method="POST"
                                                    action="{{ route('admin.suppliers.toggle', $supplier) }}"
                                                    class="d-inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-sm {{ $supplier->is_active ? 'btn-secondary' : 'btn-success' }}"
                                                        title="{{ $supplier->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                        <i class="fas fa-{{ $supplier->is_active ? 'toggle-off' : 'toggle-on' }}"></i>
                                                    </button>
                                                </form> --}}
                                                <form method="POST"
                                                    action="{{ route('admin.suppliers.destroy', $supplier) }}"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Yakin ingin menghapus supplier {{ $supplier->name }}? Data yang sudah dihapus tidak dapat dikembalikan.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
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
                            Menampilkan {{ $suppliers->firstItem() }} - {{ $suppliers->lastItem() }}
                            dari {{ $suppliers->total() }} supplier
                        </div>
                        {{ $suppliers->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-truck fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada supplier</h5>
                        <p class="text-muted">
                            @if (request()->hasAny(['search', 'status']))
                                Tidak ada supplier yang sesuai dengan filter yang dipilih
                            @else
                                Mulai dengan menambahkan supplier baru untuk sistem inventory Anda
                            @endif
                        </p>
                        @if (!request()->hasAny(['search', 'status']))
                            <div class="mt-3">
                                <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus-circle mr-1"></i>Tambah Supplier
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Summary Cards (if suppliers exist) -->
        @if ($suppliers->count() > 0)
            <div class="row">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Supplier
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $suppliers->total() }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-truck fa-2x text-primary"></i>
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
                                        Supplier Aktif
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $suppliers->where('is_active', true)->count() }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-check-circle fa-2x text-success"></i>
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
                                        Supplier Nonaktif
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $suppliers->where('is_active', false)->count() }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-times-circle fa-2x text-warning"></i>
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
                                        Total Transaksi
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $suppliers->sum('stock_transactions_count') }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-exchange-alt fa-2x text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-admin-layout>
