<x-admin-layout title="Barang Keluar">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Input Barang Keluar</h1>
                <p class="text-muted">Formulir untuk mengurangi stok barang</p>
            </div>
            <a href="{{ route('admin.stock-transactions.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Warning Card -->
        @if($products->isEmpty())
            <div class="card border-warning shadow mb-4">
                <div class="card-body text-center">
                    <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                    <h5 class="text-warning">Tidak Ada Produk dengan Stok Tersedia</h5>
                    <p class="text-muted">Saat ini tidak ada produk yang memiliki stok untuk dikeluarkan.</p>
                    <a href="{{ route('admin.stock-transactions.stock-in') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tambah Stok Terlebih Dahulu
                    </a>
                </div>
            </div>
        @else
            <!-- Form Card -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-danger">
                        <i class="fas fa-minus-circle me-2"></i>Form Barang Keluar
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.stock-transactions.store-stock-out') }}" method="POST" id="stockOutForm">
                        @csrf

                        <div class="row">
                            <!-- Product Selection -->
                            <div class="col-md-12 mb-3">
                                <label for="product_id" class="form-label">Produk <span class="text-danger">*</span></label>
                                <select name="product_id" id="product_id" class="form-select @error('product_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}"
                                                data-stock="{{ $product->current_stock }}"
                                                data-unit="{{ $product->unit }}"
                                                data-unit-text="{{ $product->unit_text }}"
                                                data-minimum-stock="{{ $product->minimum_stock }}"
                                                {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }} ({{ $product->code }}) - Stok: {{ $product->current_stock }} {{ $product->unit_text }}
                                            @if($product->current_stock <= $product->minimum_stock)
                                                - ⚠️ Stok Rendah
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Quantity -->
                            <div class="col-md-6 mb-3">
                                <label for="quantity" class="form-label">Jumlah Keluar <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="quantity" id="quantity"
                                           class="form-control @error('quantity') is-invalid @enderror"
                                           value="{{ old('quantity') }}"
                                           placeholder="Masukkan jumlah"
                                           min="1" required>
                                    <span class="input-group-text" id="unit-display">unit</span>
                                </div>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <span id="stock-info">Pilih produk terlebih dahulu</span>
                                </div>
                            </div>

                            <!-- Transaction Date -->
                            <div class="col-md-6 mb-3">
                                <label for="transaction_date" class="form-label">Tanggal Stok Keluar <span class="text-danger">*</span></label>
                                <input type="date" name="transaction_date" id="transaction_date"
                                       class="form-control @error('transaction_date') is-invalid @enderror"
                                       value="{{ old('transaction_date', date('Y-m-d')) }}"
                                       max="{{ date('Y-m-d') }}" required>
                                @error('transaction_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan</label>
                            <textarea name="notes" id="notes"
                                      class="form-control @error('notes') is-invalid @enderror"
                                      rows="3"
                                      placeholder="Tujuan penggunaan atau catatan lainnya (opsional)"
                                      maxlength="500">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maksimal 500 karakter</div>
                        </div>

                        <!-- Product Info Card -->
                        <div id="product-info" class="card bg-light mb-3" style="display: none;">
                            <div class="card-body">
                                <h6 class="card-title text-danger">Informasi Produk</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <small class="text-muted">Stok Saat Ini:</small>
                                        <div class="fw-semibold" id="current-stock">-</div>
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted">Stok Minimum:</small>
                                        <div class="fw-semibold" id="minimum-stock">-</div>
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted">Stok Setelah Keluar:</small>
                                        <div class="fw-semibold" id="stock-after">-</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Stock Warning -->
                        <div id="stock-warning" class="alert alert-warning" style="display: none;">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <span id="warning-message"></span>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.stock-transactions.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-danger" id="submit-btn">
                                <i class="fas fa-minus me-2"></i>Keluarkan Barang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            let currentProductData = null;

            // Format number to display
            function formatNumber(amount) {
                return new Intl.NumberFormat('id-ID').format(amount);
            }

            // Calculate stock after transaction
            function calculateStockAfter() {
                const quantity = parseInt($('#quantity').val()) || 0;
                const currentStock = parseInt($('#current-stock').text().replace(/[^0-9]/g, '')) || 0;
                const minimumStock = parseInt($('#minimum-stock').text().replace(/[^0-9]/g, '')) || 0;

                // Calculate stock after
                const stockAfter = currentStock - quantity;
                $('#stock-after').text(formatNumber(stockAfter));

                // Update stock after color based on conditions
                const stockAfterElement = $('#stock-after');
                stockAfterElement.removeClass('text-success text-warning text-danger');

                if (stockAfter < 0) {
                    stockAfterElement.addClass('text-danger');
                } else if (stockAfter <= minimumStock) {
                    stockAfterElement.addClass('text-warning');
                } else {
                    stockAfterElement.addClass('text-success');
                }

                // Show warnings
                checkStockWarnings(quantity, currentStock, stockAfter, minimumStock);
            }

            // Check and display stock warnings
            function checkStockWarnings(quantity, currentStock, stockAfter, minimumStock) {
                const warningDiv = $('#stock-warning');
                const warningMessage = $('#warning-message');
                const submitBtn = $('#submit-btn');

                if (quantity > currentStock) {
                    warningMessage.html('<strong>Stok tidak mencukupi!</strong> Jumlah yang akan dikeluarkan melebihi stok tersedia.');
                    warningDiv.removeClass('alert-warning').addClass('alert-danger').show();
                    submitBtn.prop('disabled', true);
                } else if (stockAfter <= 0) {
                    warningMessage.html('<strong>Peringatan:</strong> Stok akan habis setelah transaksi ini!');
                    warningDiv.removeClass('alert-danger').addClass('alert-warning').show();
                    submitBtn.prop('disabled', false);
                } else if (stockAfter <= minimumStock) {
                    warningMessage.html('<strong>Peringatan:</strong> Stok akan berada di bawah batas minimum setelah transaksi ini!');
                    warningDiv.removeClass('alert-danger').addClass('alert-warning').show();
                    submitBtn.prop('disabled', false);
                } else {
                    warningDiv.hide();
                    submitBtn.prop('disabled', false);
                }
            }

            // Product selection change
            $('#product_id').change(function() {
                const selectedOption = $(this).find('option:selected');
                const stock = selectedOption.data('stock');
                const unit = selectedOption.data('unit');
                const unitText = selectedOption.data('unit-text');
                const minimumStock = selectedOption.data('minimum-stock');

                // Store current product data
                currentProductData = {
                    stock: stock,
                    unit: unit,
                    unit_text: unitText,
                    minimum_stock: minimumStock
                };

                if ($(this).val()) {
                    $('#product-info').show();
                    $('#current-stock').text(formatNumber(stock || 0));
                    $('#minimum-stock').text(formatNumber(minimumStock || 0));
                    $('#unit-display').text(unitText || 'unit');
                    $('#stock-info').text(`Stok tersedia: ${formatNumber(stock || 0)} ${unitText || 'unit'}`);

                    // Set max quantity
                    $('#quantity').attr('max', stock);

                    calculateStockAfter();
                } else {
                    currentProductData = null;
                    $('#product-info').hide();
                    $('#stock-warning').hide();
                    $('#unit-display').text('unit');
                    $('#stock-info').text('Pilih produk terlebih dahulu');
                    $('#quantity').removeAttr('max');
                }
            });

            // Calculate stock after when quantity changes
            $('#quantity').on('input', function() {
                calculateStockAfter();
            });

            // Form validation
            $('#stockOutForm').on('submit', function(e) {
                const quantity = parseInt($('#quantity').val());
                const currentStock = parseInt($('#current-stock').text().replace(/[^0-9]/g, '')) || 0;

                if (!quantity || quantity < 1) {
                    e.preventDefault();
                    alert('Jumlah harus diisi dan minimal 1');
                    $('#quantity').focus();
                    return false;
                }

                if (quantity > currentStock) {
                    e.preventDefault();
                    alert(`Jumlah melebihi stok tersedia. Stok tersedia: ${currentStock}`);
                    $('#quantity').focus();
                    return false;
                }

                // Confirm submission
                const productName = $('#product_id option:selected').text().split(' (')[0];
                const stockAfter = currentStock - quantity;

                let message = `Konfirmasi keluarkan barang:\n\nProduk: ${productName}\nJumlah: ${quantity}\nStok Setelahnya: ${stockAfter}`;

                if (stockAfter <= 0) {
                    message += '\n\n⚠️ PERINGATAN: Stok akan habis!';
                } else if (currentProductData && stockAfter <= currentProductData.minimum_stock) {
                    message += '\n\n⚠️ PERINGATAN: Stok akan berada di bawah batas minimum!';
                }

                message += '\n\nLanjutkan proses?';

                if (!confirm(message)) {
                    e.preventDefault();
                    return false;
                }
            });

            // Initialize form if product is pre-selected (for edit or validation errors)
            if ($('#product_id').val()) {
                $('#product_id').trigger('change');
            }

            // Auto-calculate when page loads if there are old values
            if ($('#quantity').val()) {
                calculateStockAfter();
            }

            // Prevent negative values
            $('#quantity').on('keydown', function(e) {
                // Allow: backspace, delete, tab, escape, enter
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 ||
                    // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                    (e.keyCode === 65 && e.ctrlKey === true) ||
                    (e.keyCode === 67 && e.ctrlKey === true) ||
                    (e.keyCode === 86 && e.ctrlKey === true) ||
                    (e.keyCode === 88 && e.ctrlKey === true) ||
                    // Allow: home, end, left, right, down, up
                    (e.keyCode >= 35 && e.keyCode <= 40)) {
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });

            // Real-time stock validation
            $('#quantity').on('input', function() {
                const quantity = parseInt($(this).val()) || 0;
                const currentStock = parseInt($('#current-stock').text().replace(/[^0-9]/g, '')) || 0;

                if (quantity > currentStock) {
                    $(this).addClass('is-invalid');
                    if (!$(this).siblings('.invalid-feedback').length) {
                        $(this).after('<div class="invalid-feedback">Jumlah melebihi stok tersedia</div>');
                    }
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).siblings('.invalid-feedback').remove();
                }
            });

            // Keyboard shortcuts
            $(document).keydown(function(e) {
                // Ctrl + S to submit form
                if (e.ctrlKey && e.keyCode === 83) {
                    e.preventDefault();
                    if (!$('#submit-btn').prop('disabled')) {
                        $('#stockOutForm').submit();
                    }
                }
                // Escape to go back
                if (e.keyCode === 27) {
                    if (confirm('Batalkan input barang keluar?')) {
                        window.location.href = "{{ route('admin.stock-transactions.index') }}";
                    }
                }
            });

            // Auto-focus on first input after product selection
            $('#product_id').on('change', function() {
                if ($(this).val()) {
                    setTimeout(function() {
                        $('#quantity').focus();
                    }, 100);
                }
            });

            // Responsive handling
            if ($(window).width() < 768) {
                $('.card-body').addClass('px-2');
                $('.btn').addClass('btn-sm');
            }
        });
    </script>
    @endpush
</x-admin-layout>
