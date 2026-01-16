<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>{{ $title ?? 'Admin' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="{{ asset('assets/css/fontawesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
      <!-- Dark Mode CSS -->
    <link href="{{ asset('assets/css/dark-mode.css') }}" rel="stylesheet">

    {{-- <link href="{{ asset('assets/css/gradient-dashboard.css') }}" rel="stylesheet"> --}}

    @yield('css')
</head>

<body id="page-top" class="{{ session('theme', 'light') === 'dark' ? 'dark-mode' : '' }}">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @include('layouts.sidebar')
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                @include('layouts.topbar')
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    {{ $slot }}

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>@Allright Reserved by: <a href="#" target="_blank">Rivael GTG </a></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/chart.min.js') }}"></script> <!-- Jika pakai lokal -->
    <script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>
    <!-- Theme Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('themeToggle');
            const body = document.body;

            if (themeToggle) {
                themeToggle.addEventListener('click', function() {
                    body.classList.toggle('dark-mode');

                    // Save theme preference
                    const theme = body.classList.contains('dark-mode') ? 'dark' : 'light';
                    fetch('{{ route("theme.toggle") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ theme: theme })
                    });

                    // Update icon
                    const icon = themeToggle.querySelector('i');
                    if (body.classList.contains('dark-mode')) {
                        icon.className = 'fas fa-sun';
                    } else {
                        icon.className = 'fas fa-moon';
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
