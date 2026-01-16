<x-admin-layout title="Tambah Supplier">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-plus mr-2 text-primary"></i>Tambah Supplier
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.suppliers.index') }}">Supplier</a>
                    </li>
                    <li class="breadcrumb-item active">Tambah</li>
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
                            <i class="fas fa-edit mr-2"></i>Form Tambah Supplier
                        </h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.suppliers.store') }}" id="supplier-form">
                            @csrf

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
                                               value="{{ old('name') }}"
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
                                               value="{{ old('code') }}"
                                               placeholder="Contoh: SUP001 (kosongkan untuk auto generate)"
                                               maxlength="50">
                                        @error('code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">
                                            Kosongkan untuk generate otomatis berdasarkan nama
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone" class="form-label">Nomor Telepon <small class="text-muted">(Opsional)</small></label>
                                        <input type="text"
                                               class="form-control @error('phone') is-invalid @enderror"
                                               id="phone"
                                               name="phone"
                                               value="{{ old('phone') }}"
                                               placeholder="Contoh: 08123456789"
                                               maxlength="20">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email <small class="text-muted">(Opsional)</small></label>
                                        <input type="email"
                                               class="form-control @error('email') is-invalid @enderror"
                                               id="email"
                                               name="email"
                                               value="{{ old('email') }}"
                                               placeholder="supplier@example.com">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="contact_person" class="form-label">Contact Person <small class="text-muted">(Opsional)</small></label>
                                <input type="text"
                                       class="form-control @error('contact_person') is-invalid @enderror"
                                       id="contact_person"
                                       name="contact_person"
                                       value="{{ old('contact_person') }}"
                                       placeholder="Nama person yang bisa dihubungi">
                                @error('contact_person')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="address" class="form-label">Alamat <small class="text-muted">(Opsional)</small></label>
                                <textarea class="form-control @error('address') is-invalid @enderror"
                                          id="address"
                                          name="address"
                                          rows="3"
                                          placeholder="Alamat lengkap supplier">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="is_active"
                                           name="is_active"
                                           value="1"
                                           {{ old('is_active', true) ? 'checked' : '' }}>
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
                                <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                                </a>
                                <div>
                                    <button type="reset" class="btn btn-warning mr-2">
                                        <i class="fas fa-undo mr-1"></i> Reset
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save mr-1"></i> Simpan Supplier
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Info Card -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-info">
                            <i class="fas fa-info-circle mr-2"></i>Informasi
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h6><i class="fas fa-lightbulb mr-2"></i>Tips Pengisian:</h6>
                            <ul class="mb-0 pl-3">
                                <li>Nama supplier harus unik dan mudah diingat</li>
                                <li>Kode akan di-generate otomatis jika tidak diisi</li>
                                <li>Isi informasi kontak untuk memudahkan komunikasi</li>
                                <li>Status aktif menentukan apakah supplier dapat digunakan</li>
                            </ul>
                        </div>

                        <div class="alert alert-warning">
                            <h6><i class="fas fa-exclamation-triangle mr-2"></i>Perhatian:</h6>
                            <ul class="mb-0 pl-3">
                                <li>Field bertanda <span class="text-danger">*</span> wajib diisi</li>
                                <li>Kode supplier tidak dapat diubah setelah disimpan</li>
                                <li>Pastikan data yang dimasukkan sudah benar</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Preview Card -->
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">
                            <i class="fas fa-eye mr-2"></i>Preview Data
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="preview-data">
                            <div class="mb-2">
                                <strong>Nama:</strong>
                                <span id="preview-name" class="text-muted">-</span>
                            </div>
                            <div class="mb-2">
                                <strong>Kode:</strong>
                                <span id="preview-code" class="text-muted">Auto Generate</span>
                            </div>
                            <div class="mb-2">
                                <strong>Telepon:</strong>
                                <span id="preview-phone" class="text-muted">-</span>
                            </div>
                            <div class="mb-2">
                                <strong>Email:</strong>
                                <span id="preview-email" class="text-muted">-</span>
                            </div>
                            <div class="mb-2">
                                <strong>Contact Person:</strong>
                                <span id="preview-contact" class="text-muted">-</span>
                            </div>
                            <div class="mb-2">
                                <strong>Status:</strong>
                                <span id="preview-status" class="badge badge-success">Aktif</span>
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
                $('#preview-code').text($('#code').val() || 'Auto Generate');
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

                if (name.length < 2) {
                    e.preventDefault();
                    alert('Nama supplier minimal 2 karakter!');
                    $('#name').focus();
                    return false;
                }

                // Confirm before submit
                if (!confirm('Yakin ingin menyimpan data supplier ini?')) {
                    e.preventDefault();
                    return false;
                }
            });

            // Auto generate code preview based on name
            $('#name').on('input', function() {
                var name = $(this).val().trim();
                if (name && !$('#code').val()) {
                    // Simple preview of auto-generated code
                    var nameCode = name.replace(/[^A-Za-z]/g, '').substring(0, 3).toUpperCase();
                    var month = new Date().getMonth() + 1;
                    month = month < 10 ? '0' + month : month;
                    $('#preview-code').text('SUP' + nameCode + month + '001');
                }
            });

            // Reset preview code when manual code is entered
            $('#code').on('input', function() {
                if (!$(this).val()) {
                    $('#name').trigger('input'); // Trigger auto preview
                }
            });

            // Initialize preview
            updatePreview();
        });
    </script>
    @endpush
</x-admin-layout>
