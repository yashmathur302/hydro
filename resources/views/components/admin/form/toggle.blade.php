{{-- On/off switch (Bootstrap form-switch). A hidden "0" is sent when unchecked, so the value is always submitted. --}}
@props(['name', 'label', 'checked' => false, 'hint' => null])

@php
    $id = 'field-'.str_replace(['_', '.'], '-', $name);
    $isOn = (bool) old($name, $checked);
@endphp

<div class="form-check form-switch mb-0">
    <input type="hidden" name="{{ $name }}" value="0">
    <input class="form-check-input" type="checkbox" role="switch" id="{{ $id }}" name="{{ $name }}" value="1" @checked($isOn) @if ($hint) aria-describedby="{{ $id }}-hint" @endif>
    <label class="form-check-label" for="{{ $id }}">{{ $label }}</label>
    @if ($hint)
        <div class="form-text" id="{{ $id }}-hint">{{ $hint }}</div>
    @endif
</div>
