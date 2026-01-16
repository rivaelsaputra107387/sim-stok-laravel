<x-admin-layout title="Edit Produk">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Produk</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Produk</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-semibold mb-1">Edit Produk</h4>
                <p class="text-muted mb-0">Ubah informasi produk {{ $product->name }}</p>
            </div>
            <div>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>

        <!-- Alert Messages -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="fas fa-exclamation-triangle me-2"></i>Terjadi Kesalahan!</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif


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
                                <li><strong>Satuan:</strong> Pilih antara Krg (untuk barang per karung) atau Dus (untuk barang per kardus)</li>
                                <li><strong>Harga Beli & Jual:</strong> Masukkan harga dalam rupiah</li>
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
                                <li><strong>Tanggal Expired:</strong> Isi jika produk memiliki tanggal kadaluarsa</li>

                                <li><strong>Status Aktif:</strong> Centang untuk menampilkan produk di transaksi</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <!-- Form Card -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-edit text-primary me-2"></i>Form Edit Produk
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" id="editProductForm">
                            @csrf
                            @method('PUT')

                            <!-- Basic Information -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="m-0 font-weight-bold text-dark">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Informasi Dasar
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Nama Produk -->
                                        <div class="col-md-6 mb-3">
                                            <label for="name" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                            <input type="text"
                                                   class="form-control @error('name') is-invalid @enderror"
                                                   id="name"
                                                   name="name"
                                                   value="{{ old('name', $product->name) }}"
                                                   placeholder="Masukkan nama produk"
                                                   required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Kode Produk -->
                                        <div class="col-md-6 mb-3">
                                            <label for="code" class="form-label">Kode Produk</label>
                                            <input type="text"
                                                   class="form-control @error('code') is-invalid @enderror"
                                                   id="code"
                                                   name="code"
                                                   value="{{ old('code', $product->code) }}"
                                                   placeholder="Otomatis jika kosong">
                                            @error('code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Kosongkan untuk tetap menggunakan kode yang sudah ada
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <!-- Kategori -->
                                        <div class="col-md-6 mb-3">
                                            <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                                            <select class="form-select @error('category_id') is-invalid @enderror"
                                                    id="category_id"
                                                    name="category_id"
                                                    required>
                                                <option value="">Pilih Kategori</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Satuan -->
                                        <div class="col-md-6 mb-3">
                                            <label for="unit" class="form-label">Satuan <span class="text-danger">*</span></label>
                                            <select class="form-select @error('unit') is-invalid @enderror"
                                                    id="unit"
                                                    name="unit"
                                                    required>
                                                <option value="">Pilih Satuan</option>
                                                @foreach($units as $key => $value)
                                                    <option value="{{ $value }}"
                                                            {{ old('unit', $product->unit) == $value ? 'selected' : '' }}>
                                                        {{ $key }} ({{ ucfirst($value) }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('unit')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Krg untuk barang per karung, Dus untuk barang per kardus
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Deskripsi -->
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Deskripsi</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror"
                                                  id="description"
                                                  name="description"
                                                  rows="3"
                                                  placeholder="Masukkan deskripsi produk (opsional)">{{ old('description', $product->description) }}</textarea>
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
                                        <i class="fas fa-boxes me-2"></i>
                                        Informasi Stok
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Stok Saat Ini -->
<div class="col-md-6 mb-3">
    <label for="current_stock" class="form-label">Stok Saat Ini <span class="text-danger">*</span></label>
    <input type="number"
           class="form-control"
           id="current_stock"
           name="current_stock"
           value="{{ old('current_stock', $product->current_stock) }}"
           placeholder="0"
           min="0"
           readonly> <!-- Bisa pakai readonly atau disabled -->
</div>


                                        <!-- Stok Minimum -->
                                        <div class="col-md-6 mb-3">
                                            <label for="minimum_stock" class="form-label">Stok Minimum <span class="text-danger">*</span></label>
                                            <input type="number"
                                                   class="form-control @error('minimum_stock') is-invalid @enderror"
                                                   id="minimum_stock"
                                                   name="minimum_stock"
                                                   value="{{ old('minimum_stock', $product->minimum_stock) }}"
                                                   placeholder="0"
                                                   min="0"
                                                   required>
                                            @error('minimum_stock')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">
                                                <i class="fas fa-exclamation-triangle me-1 text-warning"></i>
                                                Sistem akan memberi peringatan jika stok mencapai batas ini
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            

                            <!-- Status Aktif -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="m-0 font-weight-bold text-dark">
                                        <i class="fas fa-toggle-on me-2"></i>
                                        Status Produk
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               id="is_active"
                                               name="is_active"
                                               value="1"
                                               {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Status Aktif
                                        </label>
                                    </div>
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Produk tidak aktif tidak akan muncul dalam transaksi penjualan
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>
                                    Batal
                                </a>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-save me-1"></i>
                                    Update Produk
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Product Info Sidebar -->
            <div class="col-lg-4">
                <!-- Product Info -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-info-circle text-primary me-2"></i>Informasi Produk
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-6">
                                <small class="text-muted">Kode Produk:</small>
                                <div class="fw-medium">{{ $product->code }}</div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Status:</small>
                                <div>
                                    @if($product->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Kategori:</small>
                                <div class="fw-medium">{{ $product->category->name }}</div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Satuan:</small>
                                <div class="fw-medium">{{ ucfirst($product->unit) }}</div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Stok Status:</small>
                                <div>
                                    @if($product->current_stock == 0)
                                        <span class="badge bg-danger">Habis</span>
                                    @elseif($product->current_stock <= $product->minimum_stock)
                                        <span class="badge bg-warning">Rendah</span>
                                    @else
                                        <span class="badge bg-success">Normal</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Nilai Inventori:</small>
                                <div class="fw-medium text-success">Rp {{ number_format($product->current_stock * $product->purchase_price, 0, ',', '.') }}</div>
                            </div>
                            @if($product->expired_date)
                            <div class="col-12">
                                <small class="text-muted">Tanggal Expired:</small>
                                <div class="fw-medium">{{ $product->expired_date->format('d/m/Y') }}</div>
                                @if($product->expired_date->diffInDays(now()) <= 7)
                                    <span class="badge bg-warning">Mendekati Expired</span>
                                @endif
                            </div>
                            @endif
                        </div>

                        <hr>

                        <div class="text-center">
                            <small class="text-muted">Dibuat: {{ $product->created_at->format('d/m/Y H:i') }}</small><br>
                            <small class="text-muted">Terakhir Update: {{ $product->updated_at->format('d/m/Y H:i') }}</small>
                        </div>
                    </div>
                </div>

                <!-- Stock Alert Info -->
                <div class="card mb-4 border-left-warning">
                    <div class="card-header bg-warning text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-bell me-2"></i>
                            Info Peringatan
                        </h6>
                    </div>
                    <div class="card-body">
                        <small class="text-muted">
                            <div class="mb-2">
                                <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                <strong>Stok Minimum:</strong> Peringatan jika stok ≤ batas minimum
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-times-circle text-danger me-2"></i>
                                <strong>Stok Habis:</strong> Peringatan jika stok = 0
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-calendar-times text-info me-2"></i>
                                <strong>Mendekati Expired:</strong> Peringatan 7 hari sebelum expired
                            </div>
                            <div>
                                <i class="fas fa-ban text-danger me-2"></i>
                                <strong>Sudah Expired:</strong> Peringatan jika sudah melewati tanggal expired
                            </div>
                        </small>
                    </div>
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
                } else {
                    $('#imagePreview').attr('src', '{{ $product->image ? Storage::url($product->image) : asset('assets/img/no-image.png') }}');
                }
            });

            // Calculate profit
            function calculateProfit() {
                const purchasePrice = parseFloat($('#purchase_price').val()) || 0;
                const sellingPrice = parseFloat($('#selling_price').val()) || 0;

                if (purchasePrice > 0 && sellingPrice > 0) {
                    const profit = sellingPrice - purchasePrice;
                    const profitPercentage = purchasePrice > 0 ? ((profit / purchasePrice) * 100).toFixed(2) : 0;

                    $('#profitAmount').text('Rp ' + profit.toLocaleString('id-ID'));
                    $('#profitPercentage').text(profitPercentage + '%');
                    $('#profitAlert').show();

                    // Change alert color based on profit
                    $('#profitAlert').removeClass('alert-info alert-success alert-danger');
                    if (profit < 0) {
                        $('#profitAlert').addClass('alert-danger');
                    } else if (profit > 0) {
                        $('#profitAlert').addClass('alert-success');
                    } else {
                        $('#profitAlert').addClass('alert-info');
                    }
                } else {
                    $('#profitAlert').hide();
                }
            }

            // Calculate profit on page load
            calculateProfit();

            // Calculate profit on price change
            $('#purchase_price, #selling_price').on('input', calculateProfit);

            // Show expiry warning when date is selected
            $('#expired_date').on('change', function() {
                if ($(this).val()) {
                    $('#expiryWarning').show();
                } else {
                    $('#expiryWarning').hide();
                }
            });

            // Show expiry warning if date already exists
            if ($('#expired_date').val()) {
                $('#expiryWarning').show();
            }

            // Form validation and submission
            $('#editProductForm').on('submit', function(e) {
                const purchasePrice = parseFloat($('#purchase_price').val()) || 0;
                const sellingPrice = parseFloat($('#selling_price').val()) || 0;
                const currentStock = parseInt($('#current_stock').val()) || 0;
                const minimumStock = parseInt($('#minimum_stock').val()) || 0;

                // Validate selling price vs purchase price
                if (sellingPrice < purchasePrice) {
                    if (!confirm('Harga jual lebih rendah dari harga beli. Yakin ingin melanjutkan?')) {
                        e.preventDefault();
                        return false;
                    }
                }

                // Validate minimum stock
                if (minimumStock > currentStock) {
                    if (!confirm('Stok minimum lebih tinggi dari stok saat ini. Sistem akan langsung memberi peringatan stok rendah. Yakin ingin melanjutkan?')) {
                        e.preventDefault();
                        return false;
                    }
                }

                // Validate expired date
                const expiredDate = $('#expired_date').val();
                if (expiredDate) {
                    const today = new Date();
                    const expiry = new Date(expiredDate);
                    const diffTime = expiry - today;
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                    if (diffDays <= 7) {
                        if (!confirm('Tanggal expired kurang dari 7 hari dari sekarang. Sistem akan langsung memberi peringatan mendekati expired. Yakin ingin melanjutkan?')) {
                            e.preventDefault();
                            return false;
                        }
                    }
                }

                // Disable submit button to prevent double submission
                $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Mengupdate...');
            });
        });
    </script>
    @endpush
</x-admin-layout>
