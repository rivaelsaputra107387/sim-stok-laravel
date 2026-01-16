<x-admin-layout title="Barang Masuk">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Input Barang Masuk</h1>
                <p class="text-muted">Formulir untuk menambah stok barang ke gudang</p>
            </div>
            <a href="{{ route('admin.stock-transactions.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Form Card -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-plus-circle me-2"></i>Form Barang Masuk
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.stock-transactions.store-stock-in') }}" method="POST" id="stockInForm">
                    @csrf

                    <div class="row">
                        <!-- Product Selection -->
                        <div class="col-md-6 mb-3">
                            <label for="product_id" class="form-label">Produk <span class="text-danger">*</span></label>
                            <select name="product_id" id="product_id"
                                class="form-select @error('product_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-stock="{{ $product->current_stock }}"
                                        data-unit="{{ $product->unit_text }}" data-unit-code="{{ $product->unit }}"
                                        data-minimum-stock="{{ $product->minimum_stock }}"
                                        {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} ({{ $product->code }}) - Stok:
                                        {{ $product->current_stock }} {{ $product->unit_text }}
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Supplier Selection -->
                        <div class="col-md-6 mb-3">
                            <label for="supplier_id" class="form-label">Supplier</label>
                            <select name="supplier_id" id="supplier_id"
                                class="form-select @error('supplier_id') is-invalid @enderror">
                                <option value="">-- Pilih Supplier (Opsional) --</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}"
                                        {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Quantity -->
                        <div class="col-md-6 mb-3">
                            <label for="quantity" class="form-label">Jumlah <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="quantity" id="quantity"
                                    class="form-control @error('quantity') is-invalid @enderror"
                                    value="{{ old('quantity') }}" placeholder="Masukkan jumlah" min="1"
                                    required>
                                <span class="input-group-text" id="unit-display">unit</span>
                            </div>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Total Price -->
                        <div class="col-md-6 mb-3">
                            <label for="total_price" class="form-label">
                                Total Harga Beli <span class="text-danger">*</span>
                                <i class="fas fa-info-circle text-info" data-bs-toggle="tooltip"
                                    title="Total harga untuk seluruh quantity yang akan diinput"></i>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="total_price" id="total_price"
                                    class="form-control @error('total_price') is-invalid @enderror"
                                    value="{{ old('total_price') }}" placeholder="0" min="0" step="0.01"
                                    required>
                            </div>
                            @error('total_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Transaction Date -->
                        <div class="col-md-6 mb-3">
                            <label for="transaction_date" class="form-label">Tanggal Stok Masuk <span
                                    class="text-danger">*</span></label>
                            <input type="date" name="transaction_date" id="transaction_date"
                                class="form-control @error('transaction_date') is-invalid @enderror"
                                value="{{ old('transaction_date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}"
                                required>
                            @error('transaction_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                    </div>

                    <div class="row">
                        <!-- Expired Date -->
                        <div class="col-md-6 mb-3">
                            <label for="expired_date" class="form-label">
                                Tanggal Kadaluarsa
                                <i class="fas fa-info-circle text-info" data-bs-toggle="tooltip"
                                    title="Tanggal kadaluarsa untuk batch ini (opsional)"></i>
                            </label>
                            <input type="date" name="expired_date" id="expired_date"
                                class="form-control @error('expired_date') is-invalid @enderror"
                                value="{{ old('expired_date') }}"
                                min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                            @error('expired_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan</label>
                        <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3"
                            placeholder="Catatan tambahan tentang stok masuk ini (opsional)" maxlength="500">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Maksimal 500 karakter</div>
                    </div>

                    <!-- Product Info Card -->
                    <div id="product-info" class="card bg-light mb-3" style="display: none;">
                        <div class="card-body">
                            <h6 class="card-title text-primary">
                                <i class="fas fa-info-circle me-2"></i>Informasi Produk
                            </h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <small class="text-muted">Stok Saat Ini:</small>
                                    <div class="fw-semibold" id="current-stock">-</div>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">Satuan:</small>
                                    <div class="fw-semibold" id="product-unit">-</div>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">Stok Minimum:</small>
                                    <div class="fw-semibold" id="minimum-stock">-</div>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">Stok Setelah Ditambah:</small>
                                    <div class="fw-semibold text-success" id="stock-after">-</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Status Alert -->
                    <div id="stock-status-alert" class="alert alert-dismissible fade show" style="display: none;"
                        role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <span id="stock-message"></span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>

                    <!-- Expiry Date Alert -->
                    <div id="expiry-alert" class="alert alert-warning alert-dismissible fade show"
                        style="display: none;" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <span id="expiry-message"></span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.stock-transactions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan Barang Masuk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Initialize tooltips
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                });

                // Format number to currency
                function formatCurrency(amount) {
                    return new Intl.NumberFormat('id-ID').format(amount);
                }

                // Update stock after when quantity changes
                function updateStockAfter() {
                    const quantity = parseInt($('#quantity').val()) || 0;
                    const selectedOption = $('#product_id').find('option:selected');
                    const currentStock = parseInt(selectedOption.data('stock')) || 0;
                    const stockAfter = currentStock + quantity;
                    $('#stock-after').text(formatCurrency(stockAfter));

                    // Check stock status
                    checkStockStatus(stockAfter, selectedOption);
                }

                // Check stock status and show alerts
                function checkStockStatus(stockAfter, selectedOption) {
                    const minimumStock = parseInt(selectedOption.data('minimum-stock')) || 0;
                    const stockAlert = $('#stock-status-alert');
                    const stockMessage = $('#stock-message');

                    if (stockAfter > minimumStock) {
                        stockAlert.removeClass('alert-warning alert-danger').addClass('alert-success');
                        stockMessage.text(
                            `Stok akan menjadi ${formatCurrency(stockAfter)} unit (di atas stok minimum ${formatCurrency(minimumStock)})`
                        );
                        stockAlert.show();
                    } else if (stockAfter <= minimumStock && stockAfter > 0) {
                        stockAlert.removeClass('alert-success alert-danger').addClass('alert-warning');
                        stockMessage.text(
                            `Peringatan: Stok akan menjadi ${formatCurrency(stockAfter)} unit (masih di bawah atau sama dengan stok minimum ${formatCurrency(minimumStock)})`
                        );
                        stockAlert.show();
                    } else {
                        stockAlert.hide();
                    }
                }

                // Check expiry date
                function checkExpiryDate() {
                    const expiredDate = $('#expired_date').val();
                    const expiryAlert = $('#expiry-alert');
                    const expiryMessage = $('#expiry-message');

                    if (expiredDate) {
                        const today = new Date();
                        const expiry = new Date(expiredDate);
                        const diffTime = expiry - today;
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                        if (diffDays <= 7) {
                            expiryMessage.text(
                                `Peringatan: Produk ini akan kadaluarsa dalam ${diffDays} hari (${expiry.toLocaleDateString('id-ID')})!`
                            );
                            expiryAlert.show();
                        } else {
                            expiryAlert.hide();
                        }
                    } else {
                        expiryAlert.hide();
                    }
                }

                // Product selection change
                $('#product_id').change(function() {
                    const selectedOption = $(this).find('option:selected');
                    const stock = selectedOption.data('stock');
                    const unit = selectedOption.data('unit');
                    const unitCode = selectedOption.data('unit-code');
                    const minimumStock = selectedOption.data('minimum-stock');

                    if ($(this).val()) {
                        $('#product-info').show();
                        $('#current-stock').text(formatCurrency(stock || 0));
                        $('#product-unit').text(unit || '-');
                        $('#minimum-stock').text(formatCurrency(minimumStock || 0));
                        $('#unit-display').text(unit || 'unit');

                        updateStockAfter();
                    } else {
                        $('#product-info').hide();
                        $('#stock-status-alert').hide();
                        $('#unit-display').text('unit');
                        $('#stock-after').text('-');
                    }
                });

                // Update stock after when quantity changes
                $('#quantity').on('input', function() {
                    updateStockAfter();
                });

                // Check expiry date when date changes
                $('#expired_date').on('change', function() {
                    checkExpiryDate();
                });

                // Form validation
                $('#stockInForm').on('submit', function(e) {
                    const productId = $('#product_id').val();
                    const quantity = parseInt($('#quantity').val());
                    const totalPrice = parseFloat($('#total_price').val());

                    if (!productId) {
                        e.preventDefault();
                        alert('Produk harus dipilih');
                        $('#product_id').focus();
                        return false;
                    }

                    if (!quantity || quantity < 1) {
                        e.preventDefault();
                        alert('Jumlah harus diisi dan minimal 1');
                        $('#quantity').focus();
                        return false;
                    }

                    if (!totalPrice || totalPrice < 0) {
                        e.preventDefault();
                        alert('Total harga harus diisi dan tidak boleh negatif');
                        $('#total_price').focus();
                        return false;
                    }

                    // Confirm submission
                    const selectedOption = $('#product_id').find('option:selected');
                    const productName = selectedOption.text().split(' (')[0];
                    const unit = selectedOption.data('unit');

                    let message =
                        `Konfirmasi tambah stok:\n\nProduk: ${productName}\nJumlah: ${quantity} ${unit}\nTotal Harga: Rp ${formatCurrency(totalPrice)}`;

                    const expiredDate = $('#expired_date').val();
                    if (expiredDate) {
                        message += `\nTanggal Kadaluarsa: ${new Date(expiredDate).toLocaleDateString('id-ID')}`;
                    }

                    message += '\n\nLanjutkan?';

                    if (!confirm(message)) {
                        e.preventDefault();
                        return false;
                    }
                });

                // Initialize on page load if product is already selected
                if ($('#product_id').val()) {
                    $('#product_id').trigger('change');
                }

                // Initialize expiry check if date is already filled
                if ($('#expired_date').val()) {
                    checkExpiryDate();
                }
            });
        </script>
    @endpush
</x-admin-layout>
