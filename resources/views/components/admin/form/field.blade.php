{{--
    Wrapper for a labelled form control: label, hint, character counter and error message.
    Used by the input and textarea components so the markup stays identical.
--}}
@props(['name', 'label', 'id', 'hint' => null, 'required' => false, 'bag' => 'default', 'maxlength' => null, 'counter' => false])

@php
    $error = $errors->getBag($bag)->first($name);
@endphp

<div @class(['field', 'has-error' => $error])>
    <div class="field__label-row">
        <label class="field__label" for="{{ $id }}">
            {{ $label }}
            @if ($required)<span class="field__required" aria-hidden="true">*</span>@endif
        </label>
        @if ($counter && $maxlength)
            <span class="field__counter" data-counter-for="{{ $id }}" aria-live="polite"></span>
        @endif
    </div>

    {{ $slot }}

    @if ($hint)
        <p class="field__hint" id="{{ $id }}-hint">{{ $hint }}</p>
    @endif
    @if ($error)
        <p class="field__error" id="{{ $id }}-error">{{ $error }}</p>
    @endif
</div>
