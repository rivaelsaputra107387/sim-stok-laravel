{{-- resources/views/admin/stock-transactions/show.blade.php --}}
<x-admin-layout title="Detail Transaksi Stok">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-eye"></i> Detail Transaksi: {{ $stockTransaction->transaction_code }}
            </h1>
            <div>
                @if($stockTransaction->id === $stockTransaction->product->stockTransactions()->latest()->first()->id)
                <form action="{{ route('admin.stock-transactions.destroy', $stockTransaction) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm shadow-sm mr-2"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini? Stok akan dikembalikan ke kondisi sebelumnya.')">
                        <i class="fas fa-trash fa-sm text-white-50"></i> Hapus
                    </button>
                </form>
                @endif
                <a href="{{ route('admin.stock-transactions.index') }}" class="btn btn-secondary btn-sm shadow-sm">
                    <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.stock-transactions.index') }}">Transaksi Stok</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $stockTransaction->transaction_code }}</li>
            </ol>
        </nav>

        <div class="row">
            <!-- Transaction Details -->
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-info-circle"></i> Detail Transaksi
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <div class="row mb-3">
                                    <div class="col-sm-5 font-weight-bold">Kode Transaksi:</div>
                                    <div class="col-sm-7">
                                        <span class="badge badge-secondary">{{ $stockTransaction->transaction_code }}</span>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-5 font-weight-bold">Tipe Transaksi:</div>
                                    <div class="col-sm-7">
                                        @if($stockTransaction->type === 'in')
                                            <span class="badge badge-success">
                                                <i class="fas fa-arrow-down"></i> Barang Masuk
                                            </span>
                                        @else
                                            <span class="badge badge-danger">
                                                <i class="fas fa-arrow-up"></i> Barang Keluar
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-5 font-weight-bold">Tanggal Transaksi:</div>
                                    <div class="col-sm-7">{{ $stockTransaction->transaction_date->format('d/m/Y') }}</div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-5 font-weight-bold">User:</div>
                                    <div class="col-sm-7">
                                        <i class="fas fa-user"></i> {{ $stockTransaction->user->name }}
                                    </div>
                                </div>

                                @if($stockTransaction->supplier)
                                <div class="row mb-3">
                                    <div class="col-sm-5 font-weight-bold">Supplier:</div>
                                    <div class="col-sm-7">
                                        <i class="fas fa-truck"></i> {{ $stockTransaction->supplier->name }}
                                    </div>
                                </div>
                                @endif
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6">
                                <div class="row mb-3">
                                    <div class="col-sm-5 font-weight-bold">Jumlah:</div>
                                    <div class="col-sm-7">
                                        <span class="font-weight-bold text-primary">
                                            {{ number_format($stockTransaction->quantity) }} {{ $stockTransaction->product->unit_text }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Price information only for stock IN -->
                                @if($stockTransaction->has_price_info)


                                <div class="row mb-3">
                                    <div class="col-sm-5 font-weight-bold">Total Harga:</div>
                                    <div class="col-sm-7">
                                        <span class="font-weight-bold text-success">
                                            Rp {{ number_format($stockTransaction->total_price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                                @endif

                                <div class="row mb-3">
                                    <div class="col-sm-5 font-weight-bold">Stok Sebelum:</div>
                                    <div class="col-sm-7">{{ number_format($stockTransaction->stock_before) }}</div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-5 font-weight-bold">Stok Sesudah:</div>
                                    <div class="col-sm-7">
                                        <span class="font-weight-bold">{{ number_format($stockTransaction->stock_after) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Batch Information (only for stock IN) -->
                        @if($stockTransaction->type === 'in')
                        <hr>
                        <div class="row">

                            <div class="col-md-6">
                                @if($stockTransaction->has_expired_date)
                                <div class="row mb-3">
                                    <div class="col-sm-5 font-weight-bold">Tanggal Expired:</div>
                                    <div class="col-sm-7">
                                        @if($stockTransaction->is_expired)
                                            <span class="badge badge-danger">
                                                <i class="fas fa-times-circle"></i> {{ $stockTransaction->expired_date->format('d/m/Y') }}
                                            </span>
                                        @elseif($stockTransaction->is_near_expiry)
                                            <span class="badge badge-warning">
                                                <i class="fas fa-exclamation-triangle"></i> {{ $stockTransaction->expired_date->format('d/m/Y') }}
                                            </span>
                                        @else
                                            <span class="badge badge-info">
                                                <i class="fas fa-calendar-alt"></i> {{ $stockTransaction->expired_date->format('d/m/Y') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-5 font-weight-bold">Status Expired:</div>
                                    <div class="col-sm-7">
                                        @switch($stockTransaction->expiry_status)
                                            @case('expired')
                                                <span class="badge badge-danger">
                                                    <i class="fas fa-times-circle"></i> Sudah Expired
                                                </span>
                                                @break
                                            @case('near_expiry')
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-exclamation-triangle"></i> Akan Expired ({{ $stockTransaction->expired_date->diffInDays(now()) }} hari)
                                                </span>
                                                @break
                                            @default
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check-circle"></i> Masih Baik
                                                </span>
                                        @endswitch
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        @if($stockTransaction->notes)
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <div class="font-weight-bold mb-2">Catatan:</div>
                                <div class="alert alert-light">
                                    <i class="fas fa-sticky-note"></i> {{ $stockTransaction->notes }}
                                </div>
                            </div>
                        </div>
                        @endif

                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <small class="text-muted">
                                    <i class="fas fa-clock"></i> Dibuat: {{ $stockTransaction->created_at->format('d/m/Y H:i:s') }}
                                    @if($stockTransaction->created_at != $stockTransaction->updated_at)
                                    | Diperbarui: {{ $stockTransaction->updated_at->format('d/m/Y H:i:s') }}
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Info & Summary -->
            <div class="col-lg-4">
                <!-- Product Info -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-box"></i> Informasi Produk
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($stockTransaction->product->image)
                        <div class="text-center mb-3">
                            <img src="{{ asset('storage/' . $stockTransaction->product->image) }}"
                                 alt="{{ $stockTransaction->product->name }}"
                                 class="img-fluid rounded"
                                 style="max-height: 150px;">
                        </div>
                        @endif

                        <div class="row mb-2">
                            <div class="col-4 font-weight-bold">Nama:</div>
                            <div class="col-8">{{ $stockTransaction->product->name }}</div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-4 font-weight-bold">Kode:</div>
                            <div class="col-8">
                                <span class="badge badge-secondary">{{ $stockTransaction->product->code }}</span>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-4 font-weight-bold">Kategori:</div>
                            <div class="col-8">{{ $stockTransaction->product->category->name }}</div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-4 font-weight-bold">Satuan:</div>
                            <div class="col-8">{{ $stockTransaction->product->unit_text }}</div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-4 font-weight-bold">Stok Saat Ini:</div>
                            <div class="col-8">
                                @if($stockTransaction->product->current_stock <= 0)
                                    <span class="badge badge-danger">
                                        <i class="fas fa-times"></i> Habis ({{ $stockTransaction->product->current_stock }})
                                    </span>
                                @elseif($stockTransaction->product->current_stock <= $stockTransaction->product->minimum_stock)
                                    <span class="badge badge-warning">
                                        <i class="fas fa-exclamation-triangle"></i> {{ $stockTransaction->product->current_stock }}
                                    </span>
                                @else
                                    <span class="badge badge-success">{{ $stockTransaction->product->current_stock }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-4 font-weight-bold">Stok Minimum:</div>
                            <div class="col-8">{{ $stockTransaction->product->minimum_stock }}</div>
                        </div>

                        <hr>
                        <div class="text-center">
                            <a href="{{ route('admin.products.show', $stockTransaction->product) }}"
                               class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Lihat Detail Produk
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Stock Change Summary -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-chart-line"></i> Ringkasan Perubahan Stok
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-right">
                                    <h5 class="text-muted">Sebelum</h5>
                                    <h3 class="text-secondary">{{ number_format($stockTransaction->stock_before) }}</h3>
                                </div>
                            </div>
                            <div class="col-6">
                                <h5 class="text-muted">Sesudah</h5>
                                <h3 class="{{ $stockTransaction->type === 'in' ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($stockTransaction->stock_after) }}
                                </h3>
                            </div>
                        </div>

                        <div class="text-center mt-3">
                            @if($stockTransaction->type === 'in')
                                <i class="fas fa-arrow-up text-success fa-2x"></i>
                                <p class="text-success font-weight-bold">
                                    +{{ number_format($stockTransaction->quantity) }} {{ $stockTransaction->product->unit_text }}
                                </p>
                            @else
                                <i class="fas fa-arrow-down text-danger fa-2x"></i>
                                <p class="text-danger font-weight-bold">
                                    -{{ number_format($stockTransaction->quantity) }} {{ $stockTransaction->product->unit_text }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Purchase Value Information (only for stock IN) -->
                @if($stockTransaction->has_price_info)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-shopping-cart"></i> Informasi Pembelian
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-6 font-weight-bold">Harga Beli/Unit:</div>
                            <div class="col-6 text-right">
                                Rp {{ number_format($stockTransaction->unit_price, 0, ',', '.') }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-6 font-weight-bold">Total Pembelian:</div>
                            <div class="col-6 text-right">
                                <span class="font-weight-bold text-primary">
                                    Rp {{ number_format($stockTransaction->total_price, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-6 font-weight-bold">Nilai Stok Bertambah:</div>
                            <div class="col-6 text-right">
                                <span class="text-success">
                                    +Rp {{ number_format($stockTransaction->total_price, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Expiry Information (only for stock IN with expiry) -->
                @if($stockTransaction->type === 'in' && $stockTransaction->has_expired_date)

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-tags"></i> Informasi Batch & Kedaluwarsa
                        </h6>
                    </div>
                    <div class="card-body">


                        @if($stockTransaction->has_expired_date)
                        <div class="row mb-2">
                            <div class="col-5 font-weight-bold">Tanggal Expired:</div>
                            <div class="col-7">
                                {{ $stockTransaction->expired_date->format('d/m/Y') }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-5 font-weight-bold">Status:</div>
                            <div class="col-7">
                                @switch($stockTransaction->expiry_status)
                                    @case('expired')
                                        <span class="badge badge-danger">
                                            <i class="fas fa-times-circle"></i> Expired
                                        </span>
                                        @break
                                    @case('near_expiry')
                                        <span class="badge badge-warning">
                                            <i class="fas fa-exclamation-triangle"></i> Akan Expired
                                        </span>
                                        @break
                                    @default
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle"></i> Masih Baik
                                        </span>
                                @endswitch
                            </div>
                        </div>

                        @if($stockTransaction->expiry_status === 'near_expiry')
                        <div class="row mb-2">
                            <div class="col-5 font-weight-bold">Sisa Waktu:</div>
                            <div class="col-7 text-warning">
                                {{ $stockTransaction->expired_date->diffInDays(now()) }} hari lagi
                            </div>
                        </div>
                        @endif
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Confirmation for delete
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForms = document.querySelectorAll('form[action*="destroy"]');

            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    if (confirm('Apakah Anda yakin ingin menghapus transaksi ini?\n\nStok akan dikembalikan ke kondisi sebelumnya:\n- Stok sebelum: {{ $stockTransaction->stock_before }}\n- Stok saat ini: {{ $stockTransaction->stock_after }}')) {
                        this.submit();
                    }
                });
            });
        });
    </script>
    @endpush
</x-admin-layout>
