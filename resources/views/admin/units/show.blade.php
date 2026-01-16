<x-admin-layout title="Detail Satuan">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.units.index') }}" class="btn btn-sm btn-secondary mr-3">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <h1 class="h3 mb-0 text-gray-800">Detail Satuan</h1>
            </div>
            <div class="d-flex">
                <a href="{{ route('admin.units.edit', $unit) }}" class="btn btn-sm btn-warning mr-2">
                    <i class="fas fa-edit"></i> Edit Satuan
                </a>
                <button type="button" class="btn btn-sm btn-danger delete-btn"
                        data-id="{{ $unit->id }}" data-name="{{ $unit->name }}">
                    <i class="fas fa-trash"></i> Hapus Satuan
                </button>
            </div>
        </div>

        <!-- Unit Detail Card -->
        <div class="row">
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Informasi Satuan</h6>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input toggle-status"
                                   id="status-{{ $unit->id }}" data-id="{{ $unit->id }}"
                                   {{ $unit->is_active ? 'checked' : '' }}>
                            <label class="custom-control-label" for="status-{{ $unit->id }}">
                                <span class="badge badge-{{ $unit->is_active ? 'success' : 'secondary' }}">
                                    {{ $unit->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="text-muted small">Nama Satuan</label>
                            <div class="font-weight-bold h5 text-gray-900">{{ $unit->name }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted small">Simbol</label>
                            <div class="font-weight-bold">
                                @if($unit->symbol)
                                    <span class="badge badge-info badge-lg">{{ $unit->symbol }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted small">Jumlah Produk</label>
                            <div class="font-weight-bold">
                                <span class="badge badge-primary badge-lg">{{ $unit->products_count }}</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted small">Tanggal Dibuat</label>
                            <div class="font-weight-bold">{{ $unit->created_at->format('d F Y, H:i') }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted small">Terakhir Diperbarui</label>
                            <div class="font-weight-bold">{{ $unit->updated_at->format('d F Y, H:i') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Card -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Statistik Produk</h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-right">
                                    <div class="h4 font-weight-bold text-success">
                                        {{ $unit->products->where('is_active', true)->count() }}
                                    </div>
                                    <div class="text-muted small">Produk Aktif</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="h4 font-weight-bold text-secondary">
                                    {{ $unit->products->where('is_active', false)->count() }}
                                </div>
                                <div class="text-muted small">Produk Nonaktif</div>
                            </div>
                        </div>
                        <hr>
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-right">
                                    <div class="h4 font-weight-bold text-warning">
                                        {{ $unit->products->where('current_stock', '<=', 'minimum_stock')->where('current_stock', '>', 0)->count() }}
                                    </div>
                                    <div class="text-muted small">Stok Rendah</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="h4 font-weight-bold text-danger">
                                    {{ $unit->products->where('current_stock', '<=', 0)->count() }}
                                </div>
                                <div class="text-muted small">Stok Habis</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products List -->
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Produk yang Menggunakan Satuan Ini</h6>
                        @if($unit->products->count() > 0)
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button"
                                        id="filterDropdown" data-toggle="dropdown">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item filter-btn" href="#" data-filter="all">Semua Produk</a>
                                    <a class="dropdown-item filter-btn" href="#" data-filter="active">Produk Aktif</a>
                                    <a class="dropdown-item filter-btn" href="#" data-filter="inactive">Produk Nonaktif</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item filter-btn" href="#" data-filter="normal">Stok Normal</a>
                                    <a class="dropdown-item filter-btn" href="#" data-filter="low">Stok Rendah</a>
                                    <a class="dropdown-item filter-btn" href="#" data-filter="out">Stok Habis</a>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="card-body">
                        @if($unit->products->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered" id="productsTable">
                                    <thead>
                                        <tr>
                                            <th>Kode Produk</th>
                                            <th>Nama Produk</th>
                                            <th>Kategori</th>
                                            <th>Stok Saat Ini</th>
                                            <th>Status Stok</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($unit->products as $product)
                                            <tr data-status="{{ $product->is_active ? 'active' : 'inactive' }}"
                                                data-stock="{{ $product->current_stock <= 0 ? 'out' : ($product->current_stock <= $product->minimum_stock ? 'low' : 'normal') }}">
                                                <td>
                                                    <span class="font-weight-bold text-primary">{{ $product->code }}</span>
                                                </td>
                                                <td>
                                                    <div class="font-weight-bold">{{ $product->name }}</div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-info">{{ $product->category->name }}</span>
                                                </td>
                                                <td>
                                                    <span class="font-weight-bold">{{ number_format($product->current_stock) }}</span>
                                                    @if($unit->symbol)
                                                        <small class="text-muted">{{ $unit->symbol }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($product->current_stock <= 0)
                                                        <span class="badge badge-danger">Habis</span>
                                                    @elseif($product->current_stock <= $product->minimum_stock)
                                                        <span class="badge badge-warning">Rendah</span>
                                                    @else
                                                        <span class="badge badge-success">Normal</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge badge-{{ $product->is_active ? 'success' : 'secondary' }}">
                                                        {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('admin.products.show', $product) }}"
                                                           class="btn btn-sm btn-info" title="Detail Produk">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.products.edit', $product) }}"
                                                           class="btn btn-sm btn-warning" title="Edit Produk">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-box fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Belum ada produk yang menggunakan satuan ini</h5>
                                <p class="text-muted">Produk akan muncul di sini setelah Anda menambahkan produk dengan satuan "{{ $unit->name }}".</p>
                                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
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
        $(document).ready(function() {
            // Toggle status
            $('.toggle-status').change(function() {
                const id = $(this).data('id');
                const checkbox = $(this);

                $.ajax({
                    url: `/admin/units/${id}/toggle`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update badge
                            const badge = checkbox.next().find('.badge');
                            if (response.is_active) {
                                badge.removeClass('badge-secondary').addClass('badge-success').text('Aktif');
                            } else {
                                badge.removeClass('badge-success').addClass('badge-secondary').text('Nonaktif');
                            }

                            // Show success message
                            showAlert('success', response.message);
                        } else {
                            // Revert checkbox
                            checkbox.prop('checked', !checkbox.prop('checked'));
                            showAlert('error', response.message);
                        }
                    },
                    error: function(xhr) {
                        // Revert checkbox
                        checkbox.prop('checked', !checkbox.prop('checked'));
                        showAlert('error', 'Terjadi kesalahan saat mengubah status');
                    }
                });
            });

            // Delete unit
            $('.delete-btn').click(function() {
                const id = $(this).data('id');
                const name = $(this).data('name');

                if (confirm(`Apakah Anda yakin ingin menghapus satuan "${name}"?`)) {
                    const form = $('<form>', {
                        method: 'POST',
                        action: `/admin/units/${id}`
                    });

                    form.append($('<input>', {
                        type: 'hidden',
                        name: '_token',
                        value: '{{ csrf_token() }}'
                    }));

                    form.append($('<input>', {
                        type: 'hidden',
                        name: '_method',
                        value: 'DELETE'
                    }));

                    $('body').append(form);
                    form.submit();
                }
            });

            // Filter products
            $('.filter-btn').click(function(e) {
                e.preventDefault();
                const filter = $(this).data('filter');
                const rows = $('#productsTable tbody tr');

                if (filter === 'all') {
                    rows.show();
                } else if (filter === 'active' || filter === 'inactive') {
                    rows.hide();
                    rows.filter(`[data-status="${filter}"]`).show();
                } else if (filter === 'normal' || filter === 'low' || filter === 'out') {
                    rows.hide();
                    rows.filter(`[data-stock="${filter}"]`).show();
                }

                // Update button text
                $('#filterDropdown').html(`<i class="fas fa-filter"></i> ${$(this).text()}`);
            });

            // Helper function to show alerts
            function showAlert(type, message) {
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';

                const alert = $(`
                    <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                        <i class="fas ${icon}"></i> ${message}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `);

                $('.container-fluid').prepend(alert);

                setTimeout(() => {
                    alert.alert('close');
                }, 5000);
            }
        });
    </script>
    @endpush
</x-admin-layout>
