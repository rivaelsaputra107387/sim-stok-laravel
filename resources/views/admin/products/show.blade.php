<x-admin-layout title="Detail Produk - {{ $product->name }}">
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Detail Produk</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Produk</a></li>
                        <li class="breadcrumb-item active">{{ $product->name }}</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Product Info -->
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Informasi Produk</h6>
                        <div class="d-flex align-items-center">
                            <span class="badge badge-{{ $product->is_active ? 'success' : 'danger' }} mr-2">
                                {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                        class="img-fluid rounded shadow-sm mb-3">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3"
                                        style="height: 200px;">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-bold" width="150">Nama Produk:</td>
                                        <td>{{ $product->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Kode Produk:</td>
                                        <td><code>{{ $product->code }}</code></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Kategori:</td>
                                        <td>
                                            <span class="badge badge-info">{{ $product->category->name }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Satuan:</td>
                                        <td>{{ $product->unit_text }}</td>
                                    </tr>
                                    @if ($product->description)
                                        <tr>
                                            <td class="fw-bold">Deskripsi:</td>
                                            <td>{{ $product->description }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Batch Information -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Informasi Batch Masuk</h6>
                    </div>
                    <div class="card-body">
                        @if ($stockInBatches->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No. Batch</th>
                                            <th>Tanggal Masuk</th>
                                            <th>Jumlah</th>
                                            <th>Kedaluwarsa</th>
                                            <th>Supplier</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($stockInBatches as $batchNumber => $transactions)
                                            @php
                                                $batch = $transactions->first();
                                                $isExpired = $batch->expired_date && $batch->expired_date->isPast();
                                                $isNearExpiry =
                                                    $batch->expired_date &&
                                                    $batch->expired_date->between(now(), now()->addDays(7));
                                            @endphp
                                            <tr
                                                class="{{ $isExpired ? 'bg-danger-light' : ($isNearExpiry ? 'bg-warning-light' : '') }}">
                                                <td><code>{{ $batchNumber }}</code></td>
                                                <td>{{ $batch->created_at->format('d/m/Y') }}</td>
                                                <td>{{ number_format($batch->quantity) }} {{ $product->unit_text }}
                                                </td>
                                                <td>
                                                    @if ($batch->expired_date)
                                                        <span
                                                            class="badge badge-{{ $isExpired ? 'danger' : ($isNearExpiry ? 'warning' : 'success') }}">
                                                            {{ $batch->expired_date->format('d/m/Y') }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>{{ $batch->supplier->name ?? '-' }}</td>
                                                <td>
                                                    @if ($isExpired)
                                                        <span class="badge badge-danger">Expired</span>
                                                    @elseif($isNearExpiry)
                                                        <span class="badge badge-warning">Akan Expired
                                                            ({{ $batch->expired_date->diffInDays(now()) }} hari)
                                                        </span>
                                                    @elseif($batch->expired_date)
                                                        <span class="badge badge-success">Normal</span>
                                                    @else
                                                        <span class="badge badge-info">Non-expirable</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Belum ada data batch masuk</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Stock Transactions History -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Riwayat Transaksi Stok (10 Terakhir)</h6>
                    </div>
                    <div class="card-body">
                        @if ($recentTransactions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Kode Transaksi</th>
                                            <th>Jenis</th>
                                            <th>Jumlah</th>
                                            <th>Stok Sebelum</th>
                                            <th>Stok Sesudah</th>
                                            <th>Supplier</th>
                                            <th>User</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentTransactions as $transaction)
                                            <tr>
                                                <td>{{ $transaction->created_at->format('d/m/Y') }}</td>
                                                <td><code>{{ $transaction->transaction_code }}</code></td>
                                                <td>
                                                    <span
                                                        class="badge badge-{{ $transaction->type === 'in' ? 'success' : 'danger' }}">
                                                        {{ $transaction->type === 'in' ? 'Masuk' : 'Keluar' }}
                                                    </span>
                                                </td>
                                                <td class="fw-bold">{{ number_format($transaction->quantity) }}
                                                    {{ $product->unit_text }}</td>
                                                <td>{{ number_format($transaction->stock_before) }}</td>
                                                <td>{{ number_format($transaction->stock_after) }}</td>
                                                <td>{{ $transaction->supplier->name ?? '-' }}</td>
                                                <td>{{ $transaction->user->name }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Belum ada riwayat transaksi</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Stock Status -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Status Stok</h6>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <h2
                                class="fw-bold
                            @if ($product->is_out_of_stock) text-danger
                            @elseif($product->is_low_stock) text-warning
                            @else text-success @endif">
                                {{ number_format($product->current_stock) }}
                            </h2>
                            <p class="text-muted mb-0">{{ $product->unit_text }}</p>
                        </div>

                        <div class="row text-center">
                            <div class="col-12">
                                <div>
                                    <h6 class="text-muted">Stok Minimum</h6>
                                    <p class="fw-bold mb-0">{{ number_format($product->minimum_stock) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            @if ($product->is_out_of_stock)
                                <span class="badge badge-danger badge-pill px-3 py-2">Stok Habis</span>
                            @elseif($product->is_low_stock)
                                <span class="badge badge-warning badge-pill px-3 py-2">Stok Rendah</span>
                            @else
                                <span class="badge badge-success badge-pill px-3 py-2">Stok Normal</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Expiry Alert -->
                @if ($product->has_expired_batches || $product->has_near_expiry_batches)
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-danger">Perhatian Kedaluwarsa</h6>
                        </div>
                        <div class="card-body">
                            @if ($product->has_expired_batches)
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    <strong>Ada batch yang sudah expired!</strong>
                                    <p class="mb-0 small">Total batch: {{ $product->expired_batches->count() }}</p>
                                </div>
                            @endif

                            @if ($product->has_near_expiry_batches)
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    <strong>Ada batch yang akan expired!</strong>
                                    <p class="mb-0 small">
                                        Total batch: {{ $product->near_expiry_batches->count() }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Stock Alerts -->
                @if ($product->stockAlerts->count() > 0)
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-danger">Peringatan Stok</h6>
                        </div>
                        <div class="card-body">
                            @foreach ($product->stockAlerts as $alert)
                                <div
                                    class="alert alert-{{ $alert->type === 'out_of_stock' ? 'danger' : ($alert->type === 'expired' ? 'danger' : 'warning') }} mb-2">
                                    <div class="d-flex align-items-center">
                                        <i
                                            class="fas fa-{{ $alert->type === 'out_of_stock'
                                                ? 'times-circle'
                                                : ($alert->type === 'expired'
                                                    ? 'exclamation-circle'
                                                    : 'exclamation-triangle') }} mr-2"></i>
                                        <small>{{ $alert->message }}</small>
                                    </div>
                                    <small class="text-muted">{{ $alert->created_at->diffForHumans() }}</small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Product Meta -->
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Informasi Tambahan</h6>
                    </div>
                    <div class="card-body">
                        <small class="text-muted">
                            <div class="mb-2">
                                <strong>Dibuat:</strong><br>
                                {{ $product->created_at->format('d/m/Y H:i') }}<br>
                                <small>({{ $product->created_at->diffForHumans() }})</small>
                            </div>
                            <div class="mb-2">
                                <strong>Terakhir Diupdate:</strong><br>
                                {{ $product->updated_at->format('d/m/Y H:i') }}<br>
                                <small>({{ $product->updated_at->diffForHumans() }})</small>
                            </div>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Auto refresh stock status every 30 seconds
            setInterval(function() {
                window.location.reload();
            }, 30000);
        </script>
    @endpush
</x-admin-layout>
