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
    $id = $attributes->get('id', 'field-'.str_replace('_', '-', $name));
    $hasError = $errors->getBag($bag)->has($name);
    $describedBy = collect([$hint ? $id.'-hint' : null, $hasError ? $id.'-error' : null])->filter()->implode(' ');
    $currentValue = $type === 'password' ? null : old($name, $value);
@endphp

<x-admin.form.field :$name :$label :$id :$hint :$required :$bag :$maxlength :$counter>
    <div @class(['input-wrap', 'input-wrap--password' => $type === 'password'])>
        <input
            {{ $attributes->except('id')->class(['input']) }}
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
            <button type="button" class="input-wrap__toggle" data-password-toggle="{{ $id }}" aria-label="Show password" aria-pressed="false">
                <x-admin.icon name="eye" class="icon--show" />
                <x-admin.icon name="eye-off" class="icon--hide" />
            </button>
        @endif
    </div>
</x-admin.form.field>
