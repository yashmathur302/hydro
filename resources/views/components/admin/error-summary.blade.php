{{-- Summary of validation errors at the top of a form. --}}
@props(['bag' => 'default'])

@if ($errors->getBag($bag)->any())
    <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
        <x-admin.icon name="alert" class="icon-20 flex-shrink-0" />
        <div>Please fix the highlighted {{ Str::plural('field', $errors->getBag($bag)->count()) }}.</div>
    </div>
@endif
