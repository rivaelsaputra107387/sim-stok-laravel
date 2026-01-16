<x-admin-layout title="Edit Supplier">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-edit mr-2 text-primary"></i>Edit Supplier
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.suppliers.index') }}">Supplier</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.suppliers.show', $supplier) }}">{{ $supplier->name }}</a>
                    </li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>

        <!-- Alerts -->
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
                <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <!-- Form Card -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-edit mr-2"></i>Form Edit Supplier
                        </h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.suppliers.update', $supplier) }}" id="supplier-form">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label">
                                            Nama Supplier <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               class="form-control @error('name') is-invalid @enderror"
                                               id="name"
                                               name="name"
                                               value="{{ old('name', $supplier->name) }}"
                                               placeholder="Masukkan nama supplier"
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="code" class="form-label">
                                            Kode Supplier <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               class="form-control @error('code') is-invalid @enderror"
                                               id="code"
                                               name="code"
                                               value="{{ old('code', $supplier->code) }}"
                                               placeholder="Contoh: SUP001"
                                               maxlength="50"
                                               required>
                                        @error('code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">
                                            Kode harus unik dan tidak boleh sama dengan supplier lain
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone" class="form-label">Nomor Telepon</label>
                                        <input type="text"
                                               class="form-control @error('phone') is-invalid @enderror"
                                               id="phone"
                                               name="phone"
                                               value="{{ old('phone', $supplier->phone) }}"
                                               placeholder="Contoh: 08123456789"
                                               maxlength="20">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email"
                                               class="form-control @error('email') is-invalid @enderror"
                                               id="email"
                                               name="email"
                                               value="{{ old('email', $supplier->email) }}"
                                               placeholder="supplier@example.com">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="contact_person" class="form-label">Contact Person</label>
                                <input type="text"
                                       class="form-control @error('contact_person') is-invalid @enderror"
                                       id="contact_person"
                                       name="contact_person"
                                       value="{{ old('contact_person', $supplier->contact_person) }}"
                                       placeholder="Nama person yang bisa dihubungi">
                                @error('contact_person')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="address" class="form-label">Alamat</label>
                                <textarea class="form-control @error('address') is-invalid @enderror"
                                          id="address"
                                          name="address"
                                          rows="3"
                                          placeholder="Alamat lengkap supplier">{{ old('address', $supplier->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                           <div class="form-group">
    <div class="form-check form-switch">
        <!-- Hidden to send "0" if checkbox is not checked -->
        <input type="hidden" name="is_active" value="0">

        <input class="form-check-input"
               type="checkbox"
               id="is_active"
               name="is_active"
               value="1"
               {{ old('is_active', $supplier->is_active) ? 'checked' : '' }}>
        <label class="form-check-label" for="is_active">
            Status Aktif
        </label>
    </div>
    <small class="form-text text-muted">
        Supplier aktif dapat digunakan dalam transaksi
    </small>
</div>


                            <hr>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.suppliers.show', $supplier) }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                                </a>
                                <div>
                                    <button type="reset" class="btn btn-warning mr-2">
                                        <i class="fas fa-undo mr-1"></i> Reset
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save mr-1"></i> Update Supplier
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Current Data Card -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-info">
                            <i class="fas fa-database mr-2"></i>Data Saat Ini
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="current-data">
                            <div class="mb-2">
                                <strong>Nama:</strong>
                                <span class="text-dark">{{ $supplier->name }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Kode:</strong>
                                <span class="badge badge-info">{{ $supplier->code }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Telepon:</strong>
                                <span class="text-dark">{{ $supplier->phone ?: '-' }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Email:</strong>
                                <span class="text-dark">{{ $supplier->email ?: '-' }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Contact Person:</strong>
                                <span class="text-dark">{{ $supplier->contact_person ?: '-' }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Status:</strong>
                                @if($supplier->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Non-Aktif</span>
                                @endif
                            </div>
                            <div class="mb-2">
                                <strong>Dibuat:</strong>
                                <span class="text-muted">{{ $supplier->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Diupdate:</strong>
                                <span class="text-muted">{{ $supplier->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-warning">
                            <i class="fas fa-info-circle mr-2"></i>Informasi Edit
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-exclamation-triangle mr-2"></i>Perhatian:</h6>
                            <ul class="mb-0 pl-3">
                                <li>Pastikan kode supplier tidak sama dengan supplier lain</li>
                                <li>Perubahan status akan mempengaruhi transaksi baru</li>
                                <li>Data yang sudah disimpan tidak dapat dikembalikan</li>
                            </ul>
                        </div>

                        @if($supplier->stockTransactions()->count() > 0)
                        <div class="alert alert-info">
                            <h6><i class="fas fa-history mr-2"></i>Riwayat Transaksi:</h6>
                            <p class="mb-1">Supplier ini memiliki <strong>{{ $supplier->stockTransactions()->count() }}</strong> transaksi</p>
                            <small class="text-muted">Hati-hati saat mengubah status menjadi non-aktif</small>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Preview Card -->
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">
                            <i class="fas fa-eye mr-2"></i>Preview Perubahan
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="preview-data">
                            <div class="mb-2">
                                <strong>Nama:</strong>
                                <span id="preview-name" class="text-primary">{{ $supplier->name }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Kode:</strong>
                                <span id="preview-code" class="text-primary">{{ $supplier->code }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Telepon:</strong>
                                <span id="preview-phone" class="text-primary">{{ $supplier->phone ?: '-' }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Email:</strong>
                                <span id="preview-email" class="text-primary">{{ $supplier->email ?: '-' }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Contact Person:</strong>
                                <span id="preview-contact" class="text-primary">{{ $supplier->contact_person ?: '-' }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Status:</strong>
                                <span id="preview-status" class="badge {{ $supplier->is_active ? 'badge-success' : 'badge-danger' }}">
                                    {{ $supplier->is_active ? 'Aktif' : 'Non-Aktif' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Real-time preview
            function updatePreview() {
                $('#preview-name').text($('#name').val() || '-');
                $('#preview-code').text($('#code').val() || '-');
                $('#preview-phone').text($('#phone').val() || '-');
                $('#preview-email').text($('#email').val() || '-');
                $('#preview-contact').text($('#contact_person').val() || '-');

                if ($('#is_active').is(':checked')) {
                    $('#preview-status').removeClass('badge-danger').addClass('badge-success').text('Aktif');
                } else {
                    $('#preview-status').removeClass('badge-success').addClass('badge-danger').text('Non-Aktif');
                }
            }

            // Update preview on input change
            $('#name, #code, #phone, #email, #contact_person').on('input', updatePreview);
            $('#is_active').on('change', updatePreview);

            // Form validation
            $('#supplier-form').submit(function(e) {
                var name = $('#name').val().trim();
                var code = $('#code').val().trim();

                if (name.length < 2) {
                    e.preventDefault();
                    alert('Nama supplier minimal 2 karakter!');
                    $('#name').focus();
                    return false;
                }

                if (code.length < 3) {
                    e.preventDefault();
                    alert('Kode supplier minimal 3 karakter!');
                    $('#code').focus();
                    return false;
                }

                // Confirm before submit
                if (!confirm('Yakin ingin menyimpan perubahan data supplier ini?')) {
                    e.preventDefault();
                    return false;
                }
            });

            // Highlight changes
            $('#name, #code, #phone, #email, #contact_person').on('input', function() {
                var $preview = $('#preview-' + $(this).attr('id').replace('_', '-'));
                if ($(this).val() !== $(this).data('original')) {
                    $preview.addClass('text-warning font-weight-bold');
                } else {
                    $preview.removeClass('text-warning font-weight-bold').addClass('text-primary');
                }
            });

            // Store original values
            $('#name').data('original', '{{ $supplier->name }}');
            $('#code').data('original', '{{ $supplier->code }}');
            $('#phone').data('original', '{{ $supplier->phone }}');
            $('#email').data('original', '{{ $supplier->email }}');
            $('#contact_person').data('original', '{{ $supplier->contact_person }}');

            // Initialize preview
            updatePreview();
        });
    </script>
    @endpush
</x-admin-layout>
