{{--
    Wrapper for a labelled form control (Hope UI "form-group"): label, optional
    character counter, the control, a hint and the validation message.
--}}
@props(['name', 'label', 'id', 'hint' => null, 'required' => false, 'bag' => 'default', 'maxlength' => null, 'counter' => false])

@php
    $error = $errors->getBag($bag)->first($name);
@endphp

<div class="form-group mb-0">
    <div class="d-flex justify-content-between align-items-baseline gap-2">
        <label class="form-label" for="{{ $id }}">
            {{ $label }}
            @if ($required)<span class="text-danger" aria-hidden="true">*</span>@endif
        </label>
        @if ($counter && $maxlength)
            <span class="form-text mt-0 field-counter" data-counter-for="{{ $id }}" aria-live="polite"></span>
        @endif
    </div>

    {{ $slot }}

    @if ($hint)
        <div class="form-text" id="{{ $id }}-hint">{{ $hint }}</div>
    @endif
    @if ($error)
        <div class="invalid-feedback d-block" id="{{ $id }}-error">{{ $error }}</div>
    @endif
</div>
