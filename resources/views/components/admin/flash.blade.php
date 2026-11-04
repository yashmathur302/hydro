{{-- Success message flashed to the session after an action. --}}
@if (session('status'))
    <div class="alert alert--success" role="status" data-dismissible>
        <x-admin.icon name="success" class="alert__icon" />
        <p class="alert__message">{{ session('status') }}</p>
        <button type="button" class="alert__close" data-dismiss aria-label="Dismiss message">
            <x-admin.icon name="close" />
        </button>
    </div>
@endif
