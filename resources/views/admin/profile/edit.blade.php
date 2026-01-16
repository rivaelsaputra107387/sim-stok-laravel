<x-admin-layout title="Edit Profil">
    <div class="container mt-4">
        <div class="card shadow-lg rounded-lg border-0">
            <div class="card-body p-5">
                <h1 class="text-center mb-5 fw-bold text-primary">Edit Profil Anda</h1>
                <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row mb-4">
                        <!-- Kolom Kiri -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">Nama</label>
                                <input type="text" name="name" id="name" class="form-control"
                                    value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">Email</label>
                                <input type="email" name="email" id="email" class="form-control"
                                    value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary px-5 py-2">
                            Simpan Perubahan
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
