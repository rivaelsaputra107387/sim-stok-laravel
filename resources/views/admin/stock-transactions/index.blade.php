<x-admin-layout title="Transaksi Stok">
    <div class="container-fluid">
        <!-- Header Card -->
        <div class="card bg-primary text-white shadow mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="fw-semibold mb-1">
                            <i class="bi bi-arrow-left-right me-2"></i>
                            Transaksi Stok
                        </h4>
                        <p class="mb-0">Kelola semua transaksi barang masuk dan keluar</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.stock-transactions.stock-in') }}" class="btn btn-light btn-sm">
                                <i class="bi bi-plus-circle me-1"></i>
                                Barang Masuk
                            </a>
                            <a href="{{ route('admin.stock-transactions.stock-out') }}"
                                class="btn btn-outline-light btn-sm">
                                <i class="bi bi-dash-circle me-1"></i>
                                Barang Keluar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- Filter Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="bi bi-funnel me-2"></i>Filter Transaksi
                </h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.stock-transactions.index') }}">
                    <div class="row g-3">
                        <!-- Search -->
                        <div class="col-md-3">
                            <label class="form-label">Pencarian</label>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                                placeholder="Kode transaksi, produk, batch...">
                        </div>

                        <!-- Type Filter -->
                        <div class="col-md-2">
                            <label class="form-label">Jenis Transaksi</label>
                            <select class="form-select" name="type">
                                <option value="">Semua</option>
                                <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>
                                    Barang Masuk
                                </option>
                                <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>
                                    Barang Keluar
                                </option>
                            </select>
                        </div>

                        <!-- Product Filter -->
                        <div class="col-md-2">
                            <label class="form-label">Produk</label>
                            <select class="form-select" name="product_id">
                                <option value="">Semua Produk</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}"
                                        {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->code }} - {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Supplier Filter -->
                        <div class="col-md-2">
                            <label class="form-label">Supplier</label>
                            <select class="form-select" name="supplier_id">
                                <option value="">Semua Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}"
                                        {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date From -->
                        <div class="col-md-1.5">
                            <label class="form-label">Dari Tanggal</label>
                            <input type="date" class="form-control" name="date_from"
                                value="{{ request('date_from') }}">
                        </div>

                        <!-- Date To -->
                        <div class="col-md-1.5">
                            <label class="form-label">Sampai Tanggal</label>
                            <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i>Filter
                            </button>
                            <a href="{{ route('admin.stock-transactions.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise me-1"></i>Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <!-- Transactions Table Card -->
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="bi bi-list-ul me-2"></i>Data Transaksi Stok
                </h6>
                <div class="text-muted small">
                    Total: {{ $transactions->total() }} transaksi
                </div>
            </div>
            <div class="card-body p-0">
                @if ($transactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="12%">Kode Transaksi</th>
                                    <th width="15%">Produk</th>
                                    <th width="8%">Jenis</th>
                                    <th width="8%">Jumlah</th>
                                    <th width="10%">Harga Satuan</th>
                                    <th width="10%">Total Harga</th>
                                    <th width="10%">Supplier</th>
                                    <th width="10%">Expired</th>
                                    <th width="8%">Tanggal</th>
                                    <th width="4%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $index => $transaction)
                                    <tr>
                                        <td class="text-center">
                                            {{ $transactions->firstItem() + $index }}
                                        </td>
                                        <td>
                                            <span class="fw-bold text-primary">
                                                {{ $transaction->transaction_code }}
                                            </span>
                                        </td>
                                        <td>
                                            <div>
                                                <span class="fw-semibold">{{ $transaction->product->name }}</span>
                                                <br>
                                                <small class="text-muted">{{ $transaction->product->code }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($transaction->type === 'in')
                                                <span class="badge bg-success">
                                                    <i class="bi bi-arrow-down me-1"></i>Masuk
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-arrow-up me-1"></i>Keluar
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="fw-semibold">{{ number_format($transaction->quantity) }}</span>
                                            <br>
                                            <small
                                                class="text-muted">{{ $transaction->product->unit->symbol ?? '' }}</small>
                                        </td>
                                        <td class="text-end">
                                            @if ($transaction->has_price_info)
                                                <span class="fw-semibold">
                                                    Rp {{ number_format($transaction->unit_price, 0, ',', '.') }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if ($transaction->has_price_info)
                                                <span class="fw-semibold">
                                                    Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($transaction->supplier)
                                                <span class="text-primary">{{ $transaction->supplier->name }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>


                                            @if ($transaction->has_expired_date)
                                                <div>
                                                    <small class="text-muted">Expired:</small>
                                                    @if ($transaction->is_expired)
                                                        <span class="badge bg-danger">
                                                            <i class="bi bi-exclamation-triangle me-1"></i>
                                                            {{ $transaction->formatted_expired_date }}
                                                        </span>
                                                    @elseif ($transaction->is_near_expiry)
                                                        <span class="badge bg-warning">
                                                            <i class="bi bi-clock me-1"></i>
                                                            {{ $transaction->formatted_expired_date }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-light text-dark">
                                                            {{ $transaction->formatted_expired_date }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @else

                                                    <span class="text-muted">-</span>

                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-center">
                                                {{ $transaction->formatted_transaction_date }}
                                                <br>
                                                <small class="text-muted">
                                                    {{ $transaction->created_at->format('H:i') }}
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.stock-transactions.show', $transaction) }}"
                                                    class="btn btn-outline-info" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <form method="POST"
                                                    action="{{ route('admin.stock-transactions.destroy', $transaction) }}"
                                                    onsubmit="return confirm('Yakin ingin menghapus transaksi ini? Stok akan dikembalikan ke kondisi sebelumnya.')"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger"
                                                        title="Hapus">
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
                    @if ($transactions->hasPages())
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    Menampilkan {{ $transactions->firstItem() }} - {{ $transactions->lastItem() }}
                                    dari {{ $transactions->total() }} transaksi
                                </div>
                                <div>
                                    {{ $transactions->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox display-1 text-muted"></i>
                        <h5 class="mt-3 text-muted">Belum ada transaksi</h5>
                        <p class="text-muted">
                            @if (request()->hasAny(['search', 'type', 'product_id', 'supplier_id', 'date_from', 'date_to']))
                                Tidak ada transaksi yang sesuai dengan filter yang dipilih
                            @else
                                Mulai dengan menambahkan transaksi barang masuk atau keluar
                            @endif
                        </p>
                        @if (!request()->hasAny(['search', 'type', 'product_id', 'supplier_id', 'date_from', 'date_to']))
                            <div class="mt-3">
                                <a href="{{ route('admin.stock-transactions.stock-in') }}"
                                    class="btn btn-primary me-2">
                                    <i class="bi bi-plus-circle me-1"></i>Barang Masuk
                                </a>
                                <a href="{{ route('admin.stock-transactions.stock-out') }}"
                                    class="btn btn-outline-primary">
                                    <i class="bi bi-dash-circle me-1"></i>Barang Keluar
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Summary Cards (if transactions exist) -->
        @if ($transactions->count() > 0)
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                        Total Barang Masuk
                                    </div>
                                    <div class="h5 mb-0 fw-bold text-gray-800">
                                        {{ number_format($transactions->where('type', 'in')->sum('quantity')) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-arrow-down-circle text-success fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs fw-bold text-danger text-uppercase mb-1">
                                        Total Barang Keluar
                                    </div>
                                    <div class="h5 mb-0 fw-bold text-gray-800">
                                        {{ number_format($transactions->where('type', 'out')->sum('quantity')) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-arrow-up-circle text-danger fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs fw-bold text-info text-uppercase mb-1">
                                        Total Pembelian Stok Masuk
                                    </div>
                                    <div class="h5 mb-0 fw-bold text-gray-800">
                                        Rp
                                        {{ number_format($transactions->where('type', 'in')->whereNotNull('total_price')->sum('total_price'), 0, ',', '.') }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-currency-dollar text-info fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                                        Kadaluarsa
                                    </div>
                                    <div class="h5 mb-0 fw-bold text-gray-800">
                                        {{ $transactions->where('type', 'in')->where(function ($t) {
                                                return $t->is_expired || $t->is_near_expiry;
                                            })->count() }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-exclamation-triangle text-warning fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-admin-layout>
