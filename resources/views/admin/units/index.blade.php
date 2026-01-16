<x-admin-layout title="Kelola Satuan">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Kelola Satuan</h1>
            <a href="{{ route('admin.units.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Satuan
            </a>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Filter Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Filter & Pencarian</h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.units.index') }}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="search">Pencarian</label>
                                <input type="text" class="form-control" id="search" name="search"
                                       value="{{ request('search') }}" placeholder="Cari nama atau simbol satuan...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="">Semua Status</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="sort_by">Urutkan</label>
                                <select class="form-control" id="sort_by" name="sort_by">
                                    <option value="name" {{ request('sort_by') === 'name' ? 'selected' : '' }}>Nama</option>
                                    <option value="symbol" {{ request('sort_by') === 'symbol' ? 'selected' : '' }}>Simbol</option>
                                    <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>Tanggal Dibuat</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="sort_order">Arah</label>
                                <select class="form-control" id="sort_order" name="sort_order">
                                    <option value="asc" {{ request('sort_order') === 'asc' ? 'selected' : '' }}>A-Z</option>
                                    <option value="desc" {{ request('sort_order') === 'desc' ? 'selected' : '' }}>Z-A</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Satuan</h6>
                <div>
                    @if($units->count() > 0)
                        <button type="button" class="btn btn-sm btn-warning" id="bulk-toggle-btn" style="display: none;">
                            <i class="fas fa-toggle-on"></i> Ubah Status
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" id="bulk-delete-btn" style="display: none;">
                            <i class="fas fa-trash"></i> Hapus Terpilih
                        </button>
                    @endif
                </div>
            </div>
            <div class="card-body">
                @if($units->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="5%">
                                        <input type="checkbox" id="select-all">
                                    </th>
                                    <th>Nama Satuan</th>
                                    <th>Simbol</th>
                                    <th>Jumlah Produk</th>
                                    <th>Status</th>
                                    <th>Tanggal Dibuat</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($units as $unit)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="unit-checkbox" value="{{ $unit->id }}">
                                        </td>
                                        <td>
                                            <div class="font-weight-bold">{{ $unit->name }}</div>
                                        </td>
                                        <td>
                                            @if($unit->symbol)
                                                <span class="badge badge-info">{{ $unit->symbol }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-secondary">{{ $unit->products_count }}</span>
                                        </td>
                                        <td>
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
                                        </td>
                                        <td>{{ $unit->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.units.show', $unit) }}"
                                                   class="btn btn-sm btn-info" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.units.edit', $unit) }}"
                                                   class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger delete-btn"
                                                        data-id="{{ $unit->id }}" data-name="{{ $unit->name }}"
                                                        title="Hapus">
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
                        <div>
                            <small class="text-muted">
                                Menampilkan {{ $units->firstItem() }} sampai {{ $units->lastItem() }}
                                dari {{ $units->total() }} data
                            </small>
                        </div>
                        <div>
                            {{ $units->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-box fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada satuan</h5>
                        <p class="text-muted">Klik tombol "Tambah Satuan" untuk menambah satuan baru.</p>
                        <a href="{{ route('admin.units.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Satuan
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Select all checkbox
            $('#select-all').change(function() {
                $('.unit-checkbox').prop('checked', this.checked);
                toggleBulkButtons();
            });

            // Individual checkbox
            $('.unit-checkbox').change(function() {
                toggleBulkButtons();

                // Update select all checkbox
                if ($('.unit-checkbox:checked').length === $('.unit-checkbox').length) {
                    $('#select-all').prop('checked', true);
                } else {
                    $('#select-all').prop('checked', false);
                }
            });

            // Toggle bulk buttons visibility
            function toggleBulkButtons() {
                const checked = $('.unit-checkbox:checked').length;
                if (checked > 0) {
                    $('#bulk-delete-btn, #bulk-toggle-btn').show();
                } else {
                    $('#bulk-delete-btn, #bulk-toggle-btn').hide();
                }
            }

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

            // Delete single unit
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

            // Bulk delete
            $('#bulk-delete-btn').click(function() {
                const selected = $('.unit-checkbox:checked').map(function() {
                    return this.value;
                }).get();

                if (selected.length === 0) {
                    showAlert('warning', 'Pilih minimal satu satuan untuk dihapus');
                    return;
                }

                if (confirm(`Apakah Anda yakin ingin menghapus ${selected.length} satuan yang dipilih?`)) {
                    $.ajax({
                        url: '{{ route("admin.units.bulk-delete") }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            ids: selected
                        },
                        success: function(response) {
                            if (response.success) {
                                showAlert('success', response.message);
                                setTimeout(() => location.reload(), 1500);
                            } else {
                                showAlert('error', response.message);
                            }
                        },
                        error: function() {
                            showAlert('error', 'Terjadi kesalahan saat menghapus data');
                        }
                    });
                }
            });

            // Bulk toggle status
            $('#bulk-toggle-btn').click(function() {
                const selected = $('.unit-checkbox:checked').map(function() {
                    return this.value;
                }).get();

                if (selected.length === 0) {
                    showAlert('warning', 'Pilih minimal satu satuan untuk diubah statusnya');
                    return;
                }

                // Show modal to choose status
                const status = confirm('Pilih OK untuk mengaktifkan, Cancel untuk menonaktifkan');

                $.ajax({
                    url: '{{ route("admin.units.bulk-toggle") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        ids: selected,
                        status: status
                    },
                    success: function(response) {
                        if (response.success) {
                            showAlert('success', response.message);
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            showAlert('error', response.message);
                        }
                    },
                    error: function() {
                        showAlert('error', 'Terjadi kesalahan saat mengubah status');
                    }
                });
            });

            // Helper function to show alerts
            function showAlert(type, message) {
                const alertClass = type === 'success' ? 'alert-success' :
                                  type === 'error' ? 'alert-danger' : 'alert-warning';
                const icon = type === 'success' ? 'fa-check-circle' :
                            type === 'error' ? 'fa-exclamation-circle' : 'fa-exclamation-triangle';

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
