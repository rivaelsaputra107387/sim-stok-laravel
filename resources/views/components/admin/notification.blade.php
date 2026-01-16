<!-- resources/views/components/notification.blade.php -->

@if ($message = session()->get('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {!! $message !!}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if ($reminderMessage)
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        {{ $reminderMessage }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
