{{-- resources/views/admin/categories/edit.blade.php --}}
<x-admin-layout title="Edit Kategori">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-edit"></i> Edit Kategori
            </h1>
            <div>
                <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-info btn-sm shadow-sm mr-2">
                    <i class="fas fa-eye fa-sm text-white-50"></i> Lihat Detail
                </a>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-sm shadow-sm">
                    <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Kategori</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.categories.show', $category) }}">{{ $category->name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>

        <!-- Form Card -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-edit"></i> Form Edit Kategori
                        </h6>
                        <div class="text-sm text-muted">
                            ID: {{ $category->id }} | Dibuat: {{ $category->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Name Field -->
                            <div class="form-group row">
                                <label for="name" class="col-sm-3 col-form-label">
                                    Nama Kategori <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name', $category->name) }}"
                                           placeholder="Masukkan nama kategori"
                                           maxlength="255">
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Nama kategori yang mudah diingat dan deskriptif
                                    </small>
                                </div>
                            </div>

                            <!-- Code Field -->
                            <div class="form-group row">
                                <label for="code" class="col-sm-3 col-form-label">
                                    Kode Kategori
                                </label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="text"
                                               class="form-control @error('code') is-invalid @enderror"
                                               id="code"
                                               name="code"
                                               value="{{ old('code', $category->code) }}"
                                               placeholder="Kode akan dibuat otomatis jika kosong"
                                               maxlength="50">
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="fas fa-barcode"></i>
                                            </span>
                                        </div>
                                        @error('code')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">
                                        Kode unik untuk kategori. Kosongkan untuk membuat otomatis dari nama
                                    </small>
                                </div>
                            </div>

                            

                            <!-- Status Field -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Status</label>
                                <div class="col-sm-9">
                                    <div class="custom-control custom-switch">
                                        <input type="hidden" name="is_active" value="0">
                                        <input type="checkbox"
                                               class="custom-control-input"
                                               id="is_active"
                                               name="is_active"
                                               value="1"
                                               {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">
                                            Kategori Aktif
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">
                                        Kategori aktif akan tersedia untuk dipilih saat menambah produk
                                    </small>
                                </div>
                            </div>

                            <!-- Info Last Updated -->
                            <div class="form-group row">
                                <div class="col-sm-9 offset-sm-3">
                                    <div class="alert alert-light border-left-info">
                                        <i class="fas fa-info-circle text-info"></i>
                                        <strong>Info:</strong> Kategori ini terakhir diperbarui pada
                                        {{ $category->updated_at->format('d/m/Y H:i') }}
                                        @if($category->products_count > 0)
                                            dan memiliki {{ $category->products_count }} produk terkait.
                                        @else
                                            dan belum memiliki produk terkait.
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="form-group row">
                                <div class="col-sm-9 offset-sm-3">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i> Update Kategori
                                            </button>
                                            <button type="reset" class="btn btn-secondary ml-2">
                                                <i class="fas fa-undo"></i> Reset
                                            </button>
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-info mr-2">
                                                <i class="fas fa-eye"></i> Lihat Detail
                                            </a>
                                            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                                                <i class="fas fa-times"></i> Batal
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        @if($category->products_count == 0)
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-left-danger shadow mb-4">
                    <div class="card-header py-3 bg-danger">
                        <h6 class="m-0 font-weight-bold text-white">
                            <i class="fas fa-exclamation-triangle"></i> Zona Berbahaya
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="text-danger mb-3">
                            <strong>Hapus Kategori:</strong> Tindakan ini tidak dapat dibatalkan dan akan menghapus kategori secara permanen.
                        </p>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Tindakan ini tidak dapat dibatalkan!')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Hapus Kategori
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Help Card -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    <i class="fas fa-info-circle"></i> Tips Edit Kategori
                                </div>
                                <div class="text-sm">
                                    <ul class="mb-0">
                                        <li>Pastikan nama kategori tidak sama dengan yang sudah ada</li>
                                        <li>Kode kategori harus unik di seluruh sistem</li>
                                        <li>Kategori yang sudah memiliki produk tidak bisa dihapus</li>
                                        <li>Menonaktifkan kategori akan menyembunyikannya dari pilihan produk baru</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-lightbulb fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Store original values
        const originalValues = {
            name: '{{ $category->name }}',
            code: '{{ $category->code }}',
            description: '{{ $category->description ?? '' }}',
            is_active: {{ $category->is_active ? 'true' : 'false' }}
        };

        // Auto generate code from name (only if code is being changed)
        document.getElementById('name').addEventListener('input', function() {
            const name = this.value;
            const codeField = document.getElementById('code');

            // Only auto-generate if code field is empty or was auto-generated
            if (!codeField.value || codeField.value === originalValues.code) {
                let code = name.toUpperCase()
                              .replace(/[^A-Z0-9]/g, '_')
                              .replace(/_+/g, '_')
                              .replace(/^_|_$/g, '')
                              .substring(0, 10);

                codeField.value = code;
            }
        });

        // Character counter for description
        const descriptionField = document.getElementById('description');
        const maxLength = 1000;

        // Create character counter
        const counterDiv = document.createElement('div');
        counterDiv.className = 'text-right mt-1';
        counterDiv.innerHTML = `<small class="text-muted"><span id="char-count">0</span>/${maxLength}</small>`;
        descriptionField.parentNode.appendChild(counterDiv);

        // Update counter
        descriptionField.addEventListener('input', function() {
            const currentLength = this.value.length;
            const counter = document.getElementById('char-count');
            counter.textContent = currentLength;

            if (currentLength > maxLength * 0.9) {
                counter.className = 'text-warning';
            } else if (currentLength === maxLength) {
                counter.className = 'text-danger';
            } else {
                counter.className = 'text-muted';
            }
        });

        // Initial count
        descriptionField.dispatchEvent(new Event('input'));

        // Reset button functionality
        document.querySelector('button[type="reset"]').addEventListener('click', function(e) {
            e.preventDefault();

            if (confirm('Apakah Anda yakin ingin mereset form ke nilai awal?')) {
                document.getElementById('name').value = originalValues.name;
                document.getElementById('code').value = originalValues.code;
                document.getElementById('description').value = originalValues.description;
                document.getElementById('is_active').checked = originalValues.is_active;

                // Update character counter
                descriptionField.dispatchEvent(new Event('input'));
            }
        });

        // Warn about unsaved changes
        let formChanged = false;

        document.querySelectorAll('input, textarea, select').forEach(element => {
            element.addEventListener('change', function() {
                formChanged = true;
            });
        });

        window.addEventListener('beforeunload', function(e) {
            if (formChanged) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        // Don't warn when submitting
        document.querySelector('form').addEventListener('submit', function() {
            formChanged = false;
        });
    </script>
    @endpush
</x-admin-layout>
