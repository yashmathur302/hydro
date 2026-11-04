{{-- On/off switch. A hidden "0" is sent when unchecked, so the value is always submitted. --}}
@props(['name', 'label', 'checked' => false, 'hint' => null])

@php
    $id = 'field-'.str_replace(['_', '.'], '-', $name);
    $isOn = (bool) old($name, $checked);
@endphp

<div class="toggle-field">
    <input type="hidden" name="{{ $name }}" value="0">
    <input class="toggle" type="checkbox" role="switch" id="{{ $id }}" name="{{ $name }}" value="1" @checked($isOn) @if ($hint) aria-describedby="{{ $id }}-hint" @endif>
    <label class="toggle-field__text" for="{{ $id }}">
        <span class="toggle-field__label">{{ $label }}</span>
        @if ($hint)
            <span class="toggle-field__hint" id="{{ $id }}-hint">{{ $hint }}</span>
        @endif
    </label>
</div>
