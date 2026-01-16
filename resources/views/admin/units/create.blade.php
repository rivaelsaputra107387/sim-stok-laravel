<x-admin-layout title="Tambah Satuan">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Tambah Satuan</h1>
            <a href="{{ route('admin.units.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
            </a>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form Tambah Satuan</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.units.store') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="name" class="form-label">Nama Satuan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name') }}"
                                       placeholder="Contoh: Pieces, Kilogram, Liter" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Nama satuan yang akan digunakan untuk produk (contoh: Pieces, Kilogram, Liter)
                                </small>
                            </div>

                            <div class="form-group">
                                <label for="symbol" class="form-label">Simbol Satuan</label>
                                <input type="text" class="form-control @error('symbol') is-invalid @enderror"
                                       id="symbol" name="symbol" value="{{ old('symbol') }}"
                                       placeholder="Contoh: pcs, kg, L" maxlength="10">
                                @error('symbol')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Singkatan atau simbol satuan (opsional, maksimal 10 karakter)
                                </small>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_active"
                                           name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">
                                        <strong>Status Aktif</strong>
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    Satuan aktif dapat digunakan untuk produk baru
                                </small>
                            </div>

                            <hr>

                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Satuan
                                </button>
                                <a href="{{ route('admin.units.index') }}" class="btn btn-secondary ml-2">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-info">Bantuan</h6>
                    </div>
                    <div class="card-body">
                        <h6 class="font-weight-bold">Tips Menambah Satuan:</h6>
                        <ul class="mb-3">
                            <li>Gunakan nama satuan yang jelas dan mudah dipahami</li>
                            <li>Simbol satuan sebaiknya singkat dan standar</li>
                            <li>Pastikan nama dan simbol belum digunakan sebelumnya</li>
                        </ul>

                        <h6 class="font-weight-bold">Contoh Satuan:</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Simbol</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Pieces</td>
                                        <td>pcs</td>
                                    </tr>
                                    <tr>
                                        <td>Kilogram</td>
                                        <td>kg</td>
                                    </tr>
                                    <tr>
                                        <td>Liter</td>
                                        <td>L</td>
                                    </tr>
                                    <tr>
                                        <td>Meter</td>
                                        <td>m</td>
                                    </tr>
                                    <tr>
                                        <td>Box</td>
                                        <td>box</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
