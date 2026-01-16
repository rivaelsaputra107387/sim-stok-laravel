{{-- resources/views/layouts/topbar.blade.php --}}
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Search -->
    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
        <div class="input-group position-relative">
            <input type="text" id="globalSearch" class="form-control bg-light border-0 small"
                placeholder="Cari produk, kategori, supplier..." aria-label="Search"
                aria-describedby="basic-addon2" autocomplete="off">
            <div class="input-group-append">
                <button class="btn btn-primary" type="button" id="searchBtn">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
            <!-- Search Results Dropdown -->
            <div id="searchResults" class="dropdown-menu shadow animated--grow-in"
                style="position: absolute; top: 100%; left: 0; right: 0; z-index: 1000; display: none; max-height: 400px; overflow-y: auto;">
                <div id="searchContent">
                    <!-- Search results will be populated here -->
                </div>
            </div>
        </div>
    </form>

    <ul class="navbar-nav ml-auto">
        <!-- Theme Toggle -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link" href="#" id="themeToggle" role="button">
                <i class="fas {{ session('theme', 'light') === 'dark' ? 'fa-sun' : 'fa-moon' }} fa-fw"></i>
            </a>
        </li>

        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
        <li class="nav-item dropdown no-arrow d-sm-none">
            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
            </a>
            <!-- Dropdown - Mobile Search -->
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                    <div class="input-group position-relative">
                        <input type="text" id="mobileGlobalSearch" class="form-control bg-light border-0 small"
                            placeholder="Cari..." aria-label="Search" aria-describedby="basic-addon2" autocomplete="off">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button" id="mobileSearchBtn">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        <!-- Nav Item - Stock Alerts -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                <span id="alertCounter" class="badge badge-danger badge-counter" style="display: none;">0</span>
            </a>
            <!-- Dropdown - Alerts -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="alertsDropdown" style="width: 350px;">
                <h6 class="dropdown-header d-flex justify-content-between align-items-center">
                    <span>Stock Alerts</span>
                    <button class="btn btn-sm btn-outline-primary" onclick="markAllAsRead()">
                        <i class="fas fa-check fa-sm"></i> Tandai Semua
                    </button>
                </h6>
                <div id="alertsList" style="max-height: 300px; overflow-y: auto;">
                    <!-- Alerts will be loaded here -->
                    <div class="text-center py-3">
                        <i class="fas fa-spinner fa-spin"></i> Memuat...
                    </div>
                </div>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-center small text-gray-500" href="{{ route('admin.alerts.index') ?? '#' }}">
                    Lihat Semua Alert
                </a>
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-inline text-gray-600 small">{{ auth()->user()->name }}</span>
                <img class="img-profile rounded-circle"
                    src="{{ auth()->user()->coveruser ? asset('storage/' . auth()->user()->coveruser) : url('logo.png') }}"
                    alt="User Cover" width="40" height="40">
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>
    </ul>
</nav>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Anda yakin ingin keluar?</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    @method('POST')
                    <button class="btn btn-primary" type="submit">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let searchTimeout;

    // Global Search Functionality
    $('#globalSearch, #mobileGlobalSearch').on('input', function() {
        const query = $(this).val();
        const searchResults = $('#searchResults');

        if (query.length < 2) {
            searchResults.hide();
            return;
        }

        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            performSearch(query);
        }, 300);
    });

    // Hide search results when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.navbar-search').length) {
            $('#searchResults').hide();
        }
    });

    // Search function
    function performSearch(query) {
        $.ajax({
            url: '{{ route("admin.search") }}',
            method: 'GET',
            data: { q: query },
            beforeSend: function() {
                $('#searchContent').html('<div class="text-center py-2"><i class="fas fa-spinner fa-spin"></i> Mencari...</div>');
                $('#searchResults').show();
            },
            success: function(response) {
                if (response.success && response.results.length > 0) {
                    let html = '';
                    response.results.forEach(function(item) {
                        html += `
                            <a class="dropdown-item d-flex align-items-center" href="${item.url}">
                                <div class="mr-3">
                                    <div class="icon-circle bg-primary">
                                        <i class="${item.icon} text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="small text-gray-500">${item.type.toUpperCase()}</div>
                                    <span class="font-weight-bold">${item.title}</span>
                                    <div class="small text-gray-600">${item.subtitle}</div>
                                    ${item.stock !== undefined ? `<div class="small text-info">Stok: ${item.stock} ${item.unit}</div>` : ''}
                                </div>
                            </a>
                        `;
                    });
                    $('#searchContent').html(html);
                } else {
                    $('#searchContent').html('<div class="dropdown-item text-center text-muted">Tidak ada hasil ditemukan</div>');
                }
            },
            error: function() {
                $('#searchContent').html('<div class="dropdown-item text-center text-danger">Terjadi kesalahan saat mencari</div>');
            }
        });
    }

    // Load notifications on page load
    loadNotifications();

    // Refresh notifications every 30 seconds
    setInterval(loadNotifications, 30000);

    // Load notifications function
    function loadNotifications() {
        // Get unread count
        $.ajax({
            url: '{{ route("admin.notifications.count") }}',
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const count = response.count;
                    const counter = $('#alertCounter');

                    if (count > 0) {
                        counter.text(count > 99 ? '99+' : count).show();
                    } else {
                        counter.hide();
                    }
                }
            }
        });

        // Get latest alerts
        $.ajax({
            url: '{{ route("admin.notifications.latest") }}',
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    let html = '';

                    if (response.alerts.length > 0) {
                        response.alerts.forEach(function(alert) {
                            html += `
                                <a class="dropdown-item d-flex align-items-center" href="${alert.url}"
                                   onclick="markAsRead(${alert.id})">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-${alert.color}">
                                            <i class="${alert.icon} text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">${alert.date} ${alert.time}</div>
                                        <span class="font-weight-bold">${alert.product_name}</span>
                                        <div class="small text-gray-600">${alert.message}</div>
                                    </div>
                                </a>
                            `;
                        });
                    } else {
                        html = '<div class="dropdown-item text-center text-muted">Tidak ada notifikasi baru</div>';
                    }

                    $('#alertsList').html(html);
                }
            },
            error: function() {
                $('#alertsList').html('<div class="dropdown-item text-center text-danger">Gagal memuat notifikasi</div>');
            }
        });
    }
});

// Mark individual alert as read
function markAsRead(alertId) {
    $.ajax({
        url: '{{ route("admin.notifications.read") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            alert_id: alertId
        },
        success: function(response) {
            if (response.success) {
                loadNotifications(); // Refresh notifications
            }
        }
    });
}

// Mark all alerts as read
function markAllAsRead() {
    $.ajax({
        url: '{{ route("admin.notifications.read") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                loadNotifications(); // Refresh notifications
                toastr.success('Semua notifikasi telah ditandai sebagai sudah dibaca');
            }
        }
    });
}
</script>

<style>
.icon-circle {
    height: 2rem;
    width: 2rem;
    border-radius: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.navbar-search .dropdown-menu {
    border: 0;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.dropdown-item:hover {
    background-color: #f8f9fc;
}

#searchResults .dropdown-item {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #e3e6f0;
}

#searchResults .dropdown-item:last-child {
    border-bottom: none;
}

#alertsList .dropdown-item {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #e3e6f0;
    white-space: normal;
}

#alertsList .dropdown-item:last-child {
    border-bottom: none;
}
</style>
