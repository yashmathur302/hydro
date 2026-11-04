{{-- Summary of validation errors at the top of a form, announced to screen readers. --}}
@props(['bag' => 'default'])

@if ($errors->getBag($bag)->any())
    <div class="alert alert--danger" role="alert" tabindex="-1" data-error-summary>
        <x-admin.icon name="alert" class="alert__icon" />
        <div class="alert__message">
            <p class="alert__title">Please fix the highlighted {{ Str::plural('field', $errors->getBag($bag)->count()) }}.</p>
        </div>
    </div>
@endif
