@props([
    'name',
    'label',
    'value' => null,
    'hint' => null,
    'required' => false,
    'bag' => 'default',
    'maxlength' => null,
    'counter' => false,
    'rows' => 3,
])

@php
    $id = $attributes->get('id', 'field-'.str_replace('_', '-', $name));
    $hasError = $errors->getBag($bag)->has($name);
    $describedBy = collect([$hint ? $id.'-hint' : null, $hasError ? $id.'-error' : null])->filter()->implode(' ');
@endphp

<x-admin.form.field :$name :$label :$id :$hint :$required :$bag :$maxlength :$counter>
    <textarea
        {{ $attributes->except('id')->class(['input', 'input--textarea']) }}
        id="{{ $id }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        @if ($maxlength) maxlength="{{ $maxlength }}" @endif
        @required($required)
        @if ($hasError) aria-invalid="true" @endif
        @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif
    >{{ old($name, $value) }}</textarea>
</x-admin.form.field>
