@props([
    'name',
    'label',
    'type' => 'text',
    'value' => null,
    'hint' => null,
    'required' => false,
    'bag' => 'default',
    'maxlength' => null,
    'counter' => false,
])

@php
    $key = trim(str_replace(['[', ']'], ['.', ''], $name), '.');
    $id = $attributes->get('id', 'field-'.str_replace(['_', '.'], '-', $key));
    $hasError = $errors->getBag($bag)->has($key);
    $describedBy = collect([$hint ? $id.'-hint' : null, $hasError ? $id.'-error' : null])->filter()->implode(' ');
    $currentValue = $type === 'password' ? null : old($key, $value);
@endphp

<x-admin.form.field :name="$key" :$label :$id :$hint :$required :$bag :$maxlength :$counter>
    <div @class(['input-group' => $type === 'password'])>
        <input
            {{ $attributes->except('id')->class(['form-control', 'is-invalid' => $hasError]) }}
            id="{{ $id }}"
            name="{{ $name }}"
            type="{{ $type }}"
            @if ($currentValue !== null) value="{{ $currentValue }}" @endif
            @if ($maxlength) maxlength="{{ $maxlength }}" @endif
            @required($required)
            @if ($hasError) aria-invalid="true" @endif
            @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif
        >
        @if ($type === 'password')
            <button type="button" class="btn btn-outline-secondary password-toggle" data-password-toggle="{{ $id }}" aria-label="Show password" aria-pressed="false">
                <x-admin.icon name="eye" class="icon-18 icon--show" />
                <x-admin.icon name="eye-off" class="icon-18 icon--hide" />
            </button>
        @endif
    </div>
</x-admin.form.field>
