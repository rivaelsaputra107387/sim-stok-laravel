{{-- resources/views/admin/categories/create.blade.php --}}
<x-admin-layout title="Tambah Kategori">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-plus"></i> Tambah Kategori
            </h1>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-sm shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
            </a>
        </div>

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Kategori</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah</li>
            </ol>
        </nav>

        <!-- Form Card -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-edit"></i> Form Tambah Kategori
                        </h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.categories.store') }}" method="POST">
                            @csrf

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
                                           value="{{ old('name') }}"
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
                                    <input type="text"
                                           class="form-control @error('code') is-invalid @enderror"
                                           id="code"
                                           name="code"
                                           value="{{ old('code') }}"
                                           placeholder="Kode akan dibuat otomatis jika kosong"
                                           maxlength="50">
                                    @error('code')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
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
                                        <input type="checkbox"
                                               class="custom-control-input"
                                               id="is_active"
                                               name="is_active"
                                               value="1"
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">
                                            Kategori Aktif
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">
                                        Kategori aktif akan tersedia untuk dipilih saat menambah produk
                                    </small>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="form-group row">
                                <div class="col-sm-9 offset-sm-3">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i> Simpan Kategori
                                            </button>
                                            <button type="reset" class="btn btn-secondary ml-2">
                                                <i class="fas fa-undo"></i> Reset
                                            </button>
                                        </div>
                                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-times"></i> Batal
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Help Card -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    <i class="fas fa-info-circle"></i> Tips
                                </div>
                                <div class="text-sm">
                                    <ul class="mb-0">
                                        <li>Nama kategori sebaiknya singkat dan mudah dipahami</li>
                                        <li>Kode kategori akan dibuat otomatis jika tidak diisi</li>
                                        <li>Gunakan deskripsi untuk menjelaskan jenis produk dalam kategori</li>
                                        <li>Kategori yang tidak aktif tidak akan muncul saat menambah produk</li>
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
        // Auto generate code from name
        document.getElementById('name').addEventListener('input', function() {
            const name = this.value;
            const codeField = document.getElementById('code');

            // Only auto-generate if code field is empty
            if (!codeField.value) {
                // Generate code: uppercase, replace spaces with underscores, limit to 10 chars
                let code = name.toUpperCase()
                              .replace(/[^A-Z0-9]/g, '_')
                              .replace(/_+/g, '_')
                              .replace(/^_|_$/g, '')
                              .substring(0, 10);

                codeField.value = code;
            }
        });

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
    </script>
    @endpush
</x-admin-layout>
