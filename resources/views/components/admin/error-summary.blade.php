{{-- Summary of validation errors at the top of a form. --}}
@props(['bag' => 'default'])

@if ($errors->getBag($bag)->any())
    <div class="banner banner--danger" role="alert">
        <x-admin.icon name="alert" class="banner__icon" />
        <p class="banner__text">Please fix the highlighted {{ Str::plural('field', $errors->getBag($bag)->count()) }}.</p>
    </div>
@endif
