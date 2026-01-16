@auth
    @if (auth()->user()->role === \App\Models\User::ROLES['Admin'])
        <script>
            window.location.href = "{{ route('admin.dashboard') }}";
        </script>
    @endif
    @if (auth()->user()->role === \App\Models\User::ROLES['Owner'])
        <script>
            window.location.href = "{{ route('admin.dashboard') }}";
        </script>
    @endif
@else
    <script>
        window.location.href = "{{ route('login') }}";
    </script>
@endauth
