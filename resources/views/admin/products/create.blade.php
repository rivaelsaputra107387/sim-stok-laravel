<x-admin-layout title="Tambah Produk">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-plus-circle text-primary mr-2"></i>
            Tambah Produk Baru
        </h1>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i>
            Kembali
        </a>
    </div>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Produk</a></li>
            <li class="breadcrumb-item active">Tambah Produk</li>
        </ol>
    </nav>

    <!-- Instructions Card -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4 border-left-info">
                <div class="card-header py-3 bg-info text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-info-circle mr-2"></i>
                        Petunjuk Pengisian Data Produk
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="font-weight-bold text-info">
                                <i class="fas fa-check-circle mr-2"></i>Wajib Diisi:
                            </h6>
                            <ul class="text-muted">
                                <li><strong>Nama Produk:</strong> Nama lengkap produk yang mudah dikenali</li>
                                <li><strong>Kategori:</strong> Pilih kategori yang sesuai dengan produk</li>
                                <li><strong>Satuan:</strong> Pilih satuan yang sesuai (pcs, kg, liter, dll)</li>
                                <li><strong>Stok Awal:</strong> Jumlah stok saat pertama kali input</li>
                                <li><strong>Stok Minimum:</strong> Batas minimum stok untuk peringatan</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="font-weight-bold text-success">
                                <i class="fas fa-lightbulb mr-2"></i>Tips:
                            </h6>
                            <ul class="text-muted">
                                <li><strong>Kode Produk:</strong> Kosongkan untuk otomatis generate berdasarkan kategori</li>
                                <li><strong>Deskripsi:</strong> Tambahkan detail produk untuk memudahkan identifikasi</li>

                                <li><strong>Status Aktif:</strong> Centang untuk menampilkan produk di sistem</li>
                            </ul>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <strong>Catatan Penting:</strong> Sistem ini fokus pada manajemen stok gudang. Tanggal expired akan dicatat per batch saat stok masuk, bukan pada level produk.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-box-open mr-2"></i>
                        Form Tambah Produk
                    </h6>
                </div>
                <div class="card-body">
                    <!-- Alert Messages -->
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong><i class="fas fa-exclamation-triangle mr-2"></i>Terjadi Kesalahan!</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
                        @csrf

                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-8">
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="m-0 font-weight-bold text-dark">
                                            <i class="fas fa-info-circle mr-2"></i>
                                            Informasi Dasar
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name" class="form-label">
                                                        Nama Produk <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text"
                                                           class="form-control @error('name') is-invalid @enderror"
                                                           id="name"
                                                           name="name"
                                                           value="{{ old('name') }}"
                                                           placeholder="Contoh: Royal Canin Kitten 2kg"
                                                           required>
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="code" class="form-label">
                                                        Kode Produk
                                                    </label>
                                                    <input type="text"
                                                           class="form-control @error('code') is-invalid @enderror"
                                                           id="code"
                                                           name="code"
                                                           value="{{ old('code') }}"
                                                           placeholder="Contoh: MKN001 (otomatis jika kosong)">
                                                    <small class="form-text text-muted">
                                                        <i class="fas fa-info-circle mr-1"></i>
                                                        Kode akan digenerate otomatis berdasarkan kategori jika kosong
                                                    </small>
                                                    @error('code')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="category_id" class="form-label">
                                                        Kategori <span class="text-danger">*</span>
                                                    </label>
                                                    <select class="form-control @error('category_id') is-invalid @enderror"
                                                            id="category_id"
                                                            name="category_id"
                                                            required>
                                                        <option value="">Pilih Kategori</option>
                                                        @foreach($categories as $category)
                                                            <option value="{{ $category->id }}"
                                                                    data-code="{{ $category->code }}"
                                                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                                {{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('category_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="unit" class="form-label">
                                                        Satuan <span class="text-danger">*</span>
                                                    </label>
                                                    <select class="form-control @error('unit') is-invalid @enderror"
                                                            id="unit"
                                                            name="unit"
                                                            required>
                                                        <option value="">Pilih Satuan</option>
                                                        @foreach($units as $key => $value)
                                                            <option value="{{ $value }}"
                                                                    {{ old('unit') == $value ? 'selected' : '' }}>
                                                                {{ $key }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <small class="form-text text-muted">
                                                        <i class="fas fa-info-circle mr-1"></i>
                                                        Pilih satuan yang sesuai dengan produk
                                                    </small>
                                                    @error('unit')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="description" class="form-label">Deskripsi</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror"
                                                      id="description"
                                                      name="description"
                                                      rows="3"
                                                      placeholder="Contoh: Makanan kucing premium untuk kitten umur 1-12 bulan">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Stock Information -->
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="m-0 font-weight-bold text-dark">
                                            <i class="fas fa-boxes mr-2"></i>
                                            Informasi Stok
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="current_stock" class="form-label">
                                                        Stok Awal <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="number"
                                                           class="form-control @error('current_stock') is-invalid @enderror"
                                                           id="current_stock"
                                                           name="current_stock"
                                                           value="{{ old('current_stock', 0) }}"
                                                           placeholder="0"
                                                           min="0"
                                                           required>
                                                    <small class="form-text text-muted">
                                                        <i class="fas fa-info-circle mr-1"></i>
                                                        Jumlah stok saat pertama kali input produk
                                                    </small>
                                                    @error('current_stock')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="minimum_stock" class="form-label">
                                                        Stok Minimum <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="number"
                                                           class="form-control @error('minimum_stock') is-invalid @enderror"
                                                           id="minimum_stock"
                                                           name="minimum_stock"
                                                           value="{{ old('minimum_stock', 0) }}"
                                                           placeholder="5"
                                                           min="0"
                                                           required>
                                                    <small class="form-text text-muted">
                                                        <i class="fas fa-exclamation-triangle mr-1 text-warning"></i>
                                                        Sistem akan memberi peringatan jika stok mencapai batas ini
                                                    </small>
                                                    @error('minimum_stock')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="alert alert-info" id="stockAlert" style="display: none;">
                                                    <i class="fas fa-info-circle mr-2"></i>
                                                    <span id="stockAlertText"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--  & Status -->
                            <div class="col-md-4">


                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="m-0 font-weight-bold text-dark">
                                            <i class="fas fa-toggle-on mr-2"></i>
                                            Status Produk
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox"
                                                       class="custom-control-input"
                                                       id="is_active"
                                                       name="is_active"
                                                       value="1"
                                                       {{ old('is_active', 1) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="is_active">
                                                    Produk Aktif
                                                </label>
                                            </div>
                                            <small class="form-text text-muted">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                Produk tidak aktif tidak akan muncul dalam sistem
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Stock Alert Info -->
                                <div class="card mb-4 border-left-warning">
                                    <div class="card-header bg-warning text-white">
                                        <h6 class="m-0 font-weight-bold">
                                            <i class="fas fa-bell mr-2"></i>
                                            Info Peringatan Stok
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <small class="text-muted">
                                            <div class="mb-2">
                                                <i class="fas fa-exclamation-triangle text-warning mr-2"></i>
                                                <strong>Stok Minimum:</strong> Peringatan jika stok ≤ batas minimum
                                            </div>
                                            <div class="mb-2">
                                                <i class="fas fa-times-circle text-danger mr-2"></i>
                                                <strong>Stok Habis:</strong> Peringatan jika stok = 0
                                            </div>
                                            <div class="mb-2">
                                                <i class="fas fa-calendar-times text-info mr-2"></i>
                                                <strong>Mendekati Expired:</strong> Peringatan berdasarkan batch stok masuk
                                            </div>
                                            <div>
                                                <i class="fas fa-ban text-danger mr-2"></i>
                                                <strong>Sudah Expired:</strong> Peringatan berdasarkan batch yang sudah expired
                                            </div>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times mr-1"></i>
                                        Batal
                                    </a>
                                    <div>
                                        <button type="reset" class="btn btn-warning mr-2">
                                            <i class="fas fa-undo mr-1"></i>
                                            Reset
                                        </button>
                                        <button type="submit" class="btn btn-primary" id="submitBtn">
                                            <i class="fas fa-save mr-1"></i>
                                            Simpan Produk
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Image preview
            $('#image').on('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imagePreview').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(file);

                    // Update label
                    $(this).next('.custom-file-label').text(file.name);
                } else {
                    $('#imagePreview').attr('src', '{{ asset('assets/img/no-image.png') }}');
                    $(this).next('.custom-file-label').text('Pilih gambar...');
                }
            });

            // Stock alert check
            function checkStockAlert() {
                const currentStock = parseInt($('#current_stock').val()) || 0;
                const minimumStock = parseInt($('#minimum_stock').val()) || 0;

                if (currentStock === 0) {
                    $('#stockAlertText').text('Stok awal adalah 0, sistem akan memberi peringatan stok habis!');
                    $('#stockAlert').removeClass('alert-info alert-warning').addClass('alert-danger').show();
                } else if (currentStock <= minimumStock && minimumStock > 0) {
                    $('#stockAlertText').text('Stok awal sudah mencapai batas minimum, sistem akan memberi peringatan stok rendah!');
                    $('#stockAlert').removeClass('alert-info alert-danger').addClass('alert-warning').show();
                } else {
                    $('#stockAlert').hide();
                }
            }

            // Check stock alert on input change
            $('#current_stock, #minimum_stock').on('input', checkStockAlert);

            // Auto generate code preview when category changes
            $('#category_id').on('change', function() {
                const categoryCode = $(this).find('option:selected').data('code');
                if (categoryCode && !$('#code').val()) {
                    const prefix = categoryCode.substring(0, 3).toUpperCase();
                    $('#code').attr('placeholder', `Auto generate: ${prefix}XXXX`);
                }
            });

            // Form validation and submission
            $('#productForm').on('submit', function(e) {
                const currentStock = parseInt($('#current_stock').val()) || 0;
                const minimumStock = parseInt($('#minimum_stock').val()) || 0;

                // Validate minimum stock
                if (minimumStock > currentStock && currentStock > 0) {
                    if (!confirm('Stok minimum lebih tinggi dari stok awal. Sistem akan langsung memberi peringatan stok rendah. Yakin ingin melanjutkan?')) {
                        e.preventDefault();
                        return false;
                    }
                }

                // Validate if stock is 0
                if (currentStock === 0) {
                    if (!confirm('Stok awal adalah 0. Sistem akan langsung memberi peringatan stok habis. Yakin ingin melanjutkan?')) {
                        e.preventDefault();
                        return false;
                    }
                }

                // Disable submit button to prevent double submission
                $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Menyimpan...');
            });

            // Reset form
            $('button[type="reset"]').on('click', function() {
                setTimeout(function() {
                    $('#imagePreview').attr('src', '{{ asset('assets/img/no-image.png') }}');
                    $('.custom-file-label').text('Pilih gambar...');
                    $('#stockAlert').hide();
                    $('#code').attr('placeholder', 'Contoh: MKN001 (otomatis jika kosong)');
                }, 100);
            });
        });
    </script>
    @endpush
</x-admin-layout>
