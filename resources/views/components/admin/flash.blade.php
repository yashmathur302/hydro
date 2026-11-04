{{-- Success or error message flashed to the session after an action, shown as a dismissible banner. --}}
@foreach (['status' => 'success', 'error' => 'danger'] as $key => $variant)
    @if (session($key))
        <div class="banner banner--{{ $variant }}" role="{{ $variant === 'danger' ? 'alert' : 'status' }}" data-dismissible>
            <x-admin.icon :name="$variant === 'danger' ? 'alert' : 'success'" class="banner__icon" />
            <p class="banner__text">{{ session($key) }}</p>
            <button type="button" class="banner__close" data-dismiss aria-label="Dismiss message">
                <x-admin.icon name="close" />
            </button>
        </div>
    @endif
@endforeach
