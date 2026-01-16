{{-- resources/views/admin/categories/index.blade.php --}}
<x-admin-layout title="Kelola Kategori">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-tags"></i> Kelola Kategori
            </h1>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Kategori
            </a>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Search and Filter Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-search"></i> Pencarian & Filter
                </h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.categories.index') }}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="search">Pencarian</label>
                                <input type="text"
                                       class="form-control form-control-sm"
                                       id="search"
                                       name="search"
                                       value="{{ request('search') }}"
                                       placeholder="Cari nama, kode...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control form-control-sm" id="status" name="status">
                                    <option value="">Semua Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="sort_by">Urutkan</label>
                                <select class="form-control form-control-sm" id="sort_by" name="sort_by">
                                    <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Nama</option>
                                    <option value="code" {{ request('sort_by') == 'code' ? 'selected' : '' }}>Kode</option>
                                    <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Tanggal Buat</option>
                                    <option value="updated_at" {{ request('sort_by') == 'updated_at' ? 'selected' : '' }}>Terakhir Update</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="sort_order">Arah</label>
                                <select class="form-control form-control-sm" id="sort_order" name="sort_order">
                                    <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>A-Z</option>
                                    <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Z-A</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-undo"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Categories Table Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-list"></i> Daftar Kategori ({{ $categories->total() }} kategori)
                </h6>
            </div>
            <div class="card-body">
                @if($categories->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="20%">Nama Kategori</th>
                                    <th width="15%">Kode</th>
                                   
                                    <th width="10%">Jumlah Produk</th>
                                    <th width="10%">Status</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $index => $category)
                                    <tr>
                                        <td>{{ $categories->firstItem() + $index }}</td>
                                        <td>
                                            <strong>{{ $category->name }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge badge-secondary">{{ $category->code }}</span>
                                        </td>

                                        <td>
                                            <span class="badge badge-info">{{ $category->products_count }}</span>
                                        </td>
                                        <td>
                                            @if($category->is_active)
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check"></i> Aktif
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">
                                                    <i class="fas fa-times"></i> Tidak Aktif
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <!-- View Detail -->
                                                <a href="{{ route('admin.categories.show', $category) }}"
                                                   class="btn btn-info btn-sm"
                                                   title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <!-- Edit -->
                                                <a href="{{ route('admin.categories.edit', $category) }}"
                                                   class="btn btn-warning btn-sm"
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                {{-- <!-- Toggle Status -->
                                                <form action="{{ route('admin.categories.toggle', $category) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin mengubah status kategori ini?')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            class="btn btn-{{ $category->is_active ? 'secondary' : 'success' }} btn-sm"
                                                            title="{{ $category->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                        <i class="fas fa-{{ $category->is_active ? 'toggle-off' : 'toggle-on' }}"></i>
                                                    </button>
                                                </form> --}}

                                                <!-- Delete -->
                                                <form action="{{ route('admin.categories.destroy', $category) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Pastikan tidak ada produk yang terkait!')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-danger btn-sm"
                                                            title="Hapus"
                                                            {{ $category->products_count > 0 ? 'disabled' : '' }}>
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <small class="text-muted">
                                Menampilkan {{ $categories->firstItem() }} - {{ $categories->lastItem() }}
                                dari {{ $categories->total() }} kategori
                            </small>
                        </div>
                        <div>
                            {{ $categories->withQueryString()->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak ada kategori yang ditemukan</h5>
                        <p class="text-muted">
                            @if(request()->hasAny(['search', 'status']))
                                Coba ubah filter pencarian Anda atau
                                <a href="{{ route('admin.categories.index') }}">reset filter</a>
                            @else
                                Belum ada kategori yang ditambahkan.
                            @endif
                        </p>
                        @if(!request()->hasAny(['search', 'status']))
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Kategori Pertama
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
