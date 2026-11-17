{{-- Success or error message flashed to the session after an action. --}}
@foreach (['status' => 'success', 'error' => 'danger'] as $key => $variant)
    @if (session($key))
        <div class="alert alert-{{ $variant }} d-flex align-items-center gap-2" role="{{ $variant === 'danger' ? 'alert' : 'status' }}" data-dismissible>
            <x-admin.icon :name="$variant === 'danger' ? 'alert' : 'success'" class="icon-20 flex-shrink-0" />
            <div class="flex-grow-1">{{ session($key) }}</div>
            <button type="button" class="btn-close" data-dismiss aria-label="Dismiss message"></button>
        </div>
    @endif
@endforeach
