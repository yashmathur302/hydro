{{-- Success message flashed to the session after an action, shown as a dismissible banner. --}}
@if (session('status'))
    <div class="banner banner--success" role="status" data-dismissible>
        <x-admin.icon name="success" class="banner__icon" />
        <p class="banner__text">{{ session('status') }}</p>
        <button type="button" class="banner__close" data-dismiss aria-label="Dismiss message">
            <x-admin.icon name="close" />
        </button>
    </div>
@endif
