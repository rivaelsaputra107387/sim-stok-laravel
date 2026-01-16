<x-admin-layout title="Ubah Password">
    <div class="container mt-4">
        <div class="card shadow-lg rounded-lg border-0">
            <div class="card-body p-5">
                <h1 class="text-center mb-5 fw-bold text-primary">Ubah Password</h1>
                <form method="POST" action="{{ route('profile.password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <!-- Input untuk password lama -->
                        <div class="mb-3">
                            <label for="current_password" class="form-label fw-bold">Password Lama</label>
                            <input type="password" name="current_password" id="current_password" class="form-control" placeholder="Masukkan password lama" required>
                            @error('current_password')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Input untuk password baru -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">Password Baru</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password baru" required>
                            @error('password')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Input untuk konfirmasi password baru -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label fw-bold">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Konfirmasi password baru" required>
                        </div>
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary px-5 py-2">
                            Ubah Password
                        </button>
                        <a href="{{ route('admin.profile.show') }}">
                            <div class="btn btn-warning mx-3">
                                Kembali
                            </div>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
