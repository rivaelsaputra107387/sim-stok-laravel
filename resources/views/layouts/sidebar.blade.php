{{-- resources/views/layouts/components/sidebar.blade.php --}}
<ul class="navbar-nav bg-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center py-3" href="{{ route('home') }}">
        <img src="{{ asset('logo.png') }}" alt="Logo" class="img-fluid" style="max-height: 60px;">
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Divider -->
    <hr class="sidebar-divider">

    @if (auth()->user()->role === \App\Models\User::ROLES['Admin'])
        <!-- Nav Item - Dashboard -->
        <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Data Master
        </div>

        <li class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.categories.index') }}">
                <i class="fas fa-fw fa-tags"></i>
                <span>Kategori</span>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.suppliers.index') }}">
                <i class="fas fa-fw fa-truck"></i>
                <span>Supplier</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Produk
        </div>

        <li
            class="nav-item {{ request()->routeIs('admin.products.index', 'admin.products.show', 'admin.products.create', 'admin.products.edit') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.products.index') }}">
                <i class="fas fa-fw fa-box"></i>
                <span>Semua Produk</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Manajemen Stok
        </div>

        <li class="nav-item {{ request()->routeIs('admin.stock-transactions.index') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.stock-transactions.index') }}">
                <i class="fas fa-fw fa-history"></i>
                <span>Riwayat Transaksi Stok</span>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('admin.stock-transactions.stock-in') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.stock-transactions.stock-in') }}">
                <i class="fas fa-fw fa-arrow-down text-success"></i>
                <span>Stok Masuk</span>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('admin.stock-transactions.stock-out') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.stock-transactions.stock-out') }}">
                <i class="fas fa-fw fa-arrow-up text-danger"></i>
                <span>Stok Keluar</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Laporan
        </div>

        <li class="nav-item {{ request()->routeIs('admin.reports.index') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.reports.index') }}">
                <i class="fas fa-fw fa-chart-pie"></i>
                <span>Laporan</span>
            </a>
        </li>
    @endif

    @if (auth()->user()->role === \App\Models\User::ROLES['Owner'])
        <!-- Nav Item - Dashboard -->
        <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dasbor</span>
            </a>
        </li>
        <!-- Owner specific menus can be added here when routes are defined -->
        <li class="nav-item {{ request()->routeIs('admin.reports.index') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.reports.index') }}">
                <i class="fas fa-fw fa-chart-pie"></i>
                <span>Laporan</span>
            </a>
        </li>
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Logout -->
    <li class="nav-item">
        <a class="nav-link" href="#"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-fw fa-sign-out-alt"></i>
            <span>Keluar</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
            @method('POST')
        </form>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>

<!-- Hidden Import Form Modal Trigger -->
<div id="importForm" style="display: none;">
    <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" accept=".xlsx,.xls,.csv" required>
        <button type="submit">Impor</button>
        <button type="button" onclick="document.getElementById('importForm').style.display='none'">Batal</button>
    </form>
</div>
