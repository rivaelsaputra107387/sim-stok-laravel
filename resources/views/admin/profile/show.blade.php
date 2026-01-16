<x-admin-layout title="Profil Saya">
    <div class="container mt-5">
        <div class="card shadow-lg rounded-lg border-0">
            <div class="card-body p-4">
                <!-- Alert -->
                @if ($success = session()->get('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{!! $success !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Profile Header -->
                <div class="text-center mb-4">
                    <img src="{{ $user->coveruser ? asset('storage/' . $user->coveruser) : asset('logo.png') }}"
                        alt="User Profile Picture" class="rounded-circle border shadow-sm mb-3" width="120" height="120">
                    <h4 class="fw-bold mb-0">{{ $user->name }}</h4>
                    <p class="text-muted"><i class="fas fa-user-tag me-1"></i>{{ $user->role }}</p>
                    <a href="{{ route('admin.profile.edit') }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-1"></i>Edit Profil
                    </a>
                </div>

                <!-- Divider -->
                <hr class="my-4">

                <!-- Profile Details -->
                <div class="row row-cols-1 row-cols-md-2 g-4">
                    <div class="col">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <h6 class="fw-bold text-primary"><i class="fas fa-envelope me-2"></i>Email</h6>
                                <p class="text-muted mb-0">{{ $user->email }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <h6 class="fw-bold text-primary"><i class="fas fa-lock me-2"></i>Password</h6>
                                <a href="{{ route('profile.password.edit') }}" class="text-warning">Ubah Password</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
