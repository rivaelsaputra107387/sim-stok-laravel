<x-admin-layout title="Edit Satuan">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Edit Satuan</h1>
            <a href="{{ route('admin.units.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
            </a>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form Edit Satuan</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.units.update', $unit) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="name" class="form-label">Nama Satuan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name', $unit->name) }}"
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
                                       id="symbol" name="symbol" value="{{ old('symbol', $unit->symbol) }}"
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
                                           name="is_active" value="1" {{ old('is_active', $unit->is_active) ? 'checked' : '' }}>
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
                                    <i class="fas fa-save"></i> Update Satuan
                                </button>
                                <a href="{{ route('admin.units.show', $unit) }}" class="btn btn-info ml-2">
                                    <i class="fas fa-eye"></i> Lihat Detail
                                </a>
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
                        <h6 class="m-0 font-weight-bold text-info">Informasi Satuan</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td><strong>ID:</strong></td>
                                <td>{{ $unit->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Dibuat:</strong></td>
                                <td>{{ $unit->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Diperbarui:</strong></td>
                                <td>{{ $unit->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Jumlah Produk:</strong></td>
                                <td>
                                    <span class="badge badge-secondary">{{ $unit->products()->count() }}</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($unit->products()->count() > 0)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-warning">Peringatan</h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning mb-0" role="alert">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Perhatian!</strong><br>
                            Satuan ini digunakan oleh {{ $unit->products()->count() }} produk.
                            Perubahan pada satuan ini akan mempengaruhi produk terkait.
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
