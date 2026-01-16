{{-- resources/views/admin/suppliers/show.blade.php --}}
<x-admin-layout title="Detail Supplier">

    @section('css')
        <style>
            .icon-circle {
                height: 2.5rem;
                width: 2.5rem;
                border-radius: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
            }
        </style>
    @endsection

    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-truck"></i> Detail Supplier
            </h1>
            <div>
                <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="btn btn-warning btn-sm shadow-sm">
                    <i class="fas fa-edit fa-sm text-white-50"></i> Edit
                </a>
                <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary btn-sm shadow-sm">
                    <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.suppliers.index') }}">Supplier</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $supplier->name }}</li>
            </ol>
        </nav>

        <div class="row">
            <!-- Supplier Information -->
            <div class="col-lg-8">
                <!-- Basic Information Card -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-info-circle"></i> Informasi Dasar
                        </h6>
                        <div class="dropdown no-arrow">
                            <span class="badge badge-{{ $supplier->is_active ? 'success' : 'danger' }}">
                                {{ $supplier->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="font-weight-bold text-gray-800" style="width: 40%;">
                                            <i class="fas fa-building text-primary"></i> Nama Supplier
                                        </td>
                                        <td>{{ $supplier->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-gray-800">
                                            <i class="fas fa-barcode text-primary"></i> Kode Supplier
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $supplier->code }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-gray-800">
                                            <i class="fas fa-phone text-primary"></i> Telepon
                                        </td>
                                        <td>
                                            @if ($supplier->phone)
                                                <a href="tel:{{ $supplier->phone }}" class="text-decoration-none">
                                                    {{ $supplier->phone }}
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-gray-800">
                                            <i class="fas fa-envelope text-primary"></i> Email
                                        </td>
                                        <td>
                                            @if ($supplier->email)
                                                <a href="mailto:{{ $supplier->email }}" class="text-decoration-none">
                                                    {{ $supplier->email }}
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="font-weight-bold text-gray-800" style="width: 40%;">
                                            <i class="fas fa-user text-primary"></i> Contact Person
                                        </td>
                                        <td>{{ $supplier->contact_person ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-gray-800">
                                            <i class="fas fa-calendar-plus text-primary"></i> Terdaftar
                                        </td>
                                        <td>{{ $supplier->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-gray-800">
                                            <i class="fas fa-calendar-edit text-primary"></i> Terakhir Update
                                        </td>
                                        <td>{{ $supplier->updated_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-gray-800">
                                            <i class="fas fa-toggle-on text-primary"></i> Status
                                        </td>
                                        <td>
                                            <span
                                                class="badge badge-{{ $supplier->is_active ? 'success' : 'danger' }}">
                                                {{ $supplier->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        @if ($supplier->address)
                            <div class="mt-3">
                                <h6 class="font-weight-bold text-gray-800">
                                    <i class="fas fa-map-marker-alt text-primary"></i> Alamat
                                </h6>
                                <div class="card bg-light">
                                    <div class="card-body py-2">
                                        {{ $supplier->address }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Transaction History -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-history"></i> Riwayat Transaksi Terbaru
                        </h6>
                    </div>
                    <div class="card-body">
                        @if ($supplier->stockTransactions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Kode Transaksi</th>
                                            <th>Produk</th>
                                            <th>Tipe</th>
                                            <th>Jumlah</th>
                                            <th>Total Harga</th>
                                            <th>User</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($supplier->stockTransactions as $transaction)
                                            <tr>
                                                <td>{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                                                <td>
                                                    <span
                                                        class="badge badge-info">{{ $transaction->transaction_code }}</span>
                                                </td>
                                                <td>{{ $transaction->product->name }}</td>
                                                <td>
                                                    <span
                                                        class="badge badge-{{ $transaction->type === 'in' ? 'success' : 'danger' }}">
                                                        {{ $transaction->type === 'in' ? 'Masuk' : 'Keluar' }}
                                                    </span>
                                                </td>
                                                <td>{{ number_format($transaction->quantity) }}</td>
                                                <td>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                                                <td>{{ $transaction->user->name }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            @if ($supplier->stockTransactions()->count() > 10)
                                <div class="text-center mt-3">
                                    <a href="{{ route('admin.stock-transactions.index', ['supplier' => $supplier->id]) }}"
                                        class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i> Lihat Semua Transaksi
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                                <p class="text-gray-500">Belum ada transaksi dengan supplier ini</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Statistics Sidebar -->
            <div class="col-lg-4">
                <!-- Statistics Card -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-chart-bar"></i> Statistik
                        </h6>
                    </div>
                    <div class="card-body">
                        <!-- Total Transactions -->
                        <div class="d-flex align-items-center mb-3">
                            <div class="mr-3">
                                <div class="icon-circle bg-primary">
                                    <i class="fas fa-receipt text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="small text-gray-500">Total Transaksi</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($stats['total_transactions']) }}
                                </div>
                            </div>
                        </div>

                        <!-- Total Value -->
                        <div class="d-flex align-items-center mb-3">
                            <div class="mr-3">
                                <div class="icon-circle bg-success">
                                    <i class="fas fa-dollar-sign text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="small text-gray-500">Total Nilai Transaksi</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($stats['total_value'], 0, ',', '.') }}
                                </div>
                            </div>
                        </div>

                        <!-- Active Products -->
                        <div class="d-flex align-items-center mb-3">
                            <div class="mr-3">
                                <div class="icon-circle bg-info">
                                    <i class="fas fa-box text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="small text-gray-500">Produk Aktif</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($stats['active_products']) }}
                                </div>
                            </div>
                        </div>

                        <!-- Last Transaction -->
                        <div class="d-flex align-items-center">
                            <div class="mr-3">
                                <div class="icon-circle bg-warning">
                                    <i class="fas fa-clock text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="small text-gray-500">Transaksi Terakhir</div>
                                <div class="h6 mb-0 font-weight-bold text-gray-800">
                                    @if ($stats['last_transaction'])
                                        {{ $stats['last_transaction']->format('d/m/Y') }}
                                    @else
                                        <span class="text-muted">Belum ada</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-cogs"></i> Aksi Cepat
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.suppliers.edit', $supplier) }}"
                                class="btn btn-warning btn-block">
                                <i class="fas fa-edit"></i> Edit Supplier
                            </a>

                            <form action="{{ route('admin.suppliers.toggle', $supplier) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="btn btn-{{ $supplier->is_active ? 'secondary' : 'success' }} btn-block"
                                    onclick="return confirm('Yakin ingin {{ $supplier->is_active ? 'menonaktifkan' : 'mengaktifkan' }} supplier ini?')">
                                    <i class="fas fa-toggle-{{ $supplier->is_active ? 'off' : 'on' }}"></i>
                                    {{ $supplier->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>

                            @if ($stats['total_transactions'] == 0)
                                <form action="{{ route('admin.suppliers.destroy', $supplier) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-block"
                                        onclick="return confirm('Yakin ingin menghapus supplier ini? Tindakan ini tidak dapat dibatalkan!')">
                                        <i class="fas fa-trash"></i> Hapus Supplier
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
