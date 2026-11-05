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
    $key = trim(str_replace(['[', ']'], ['.', ''], $name), '.');
    $id = $attributes->get('id', 'field-'.str_replace(['_', '.'], '-', $key));
    $hasError = $errors->getBag($bag)->has($key);
    $describedBy = collect([$hint ? $id.'-hint' : null, $hasError ? $id.'-error' : null])->filter()->implode(' ');
@endphp

<x-admin.form.field :name="$key" :$label :$id :$hint :$required :$bag :$maxlength :$counter>
    <textarea
        {{ $attributes->except('id')->class(['input', 'input--textarea']) }}
        id="{{ $id }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        @if ($maxlength) maxlength="{{ $maxlength }}" @endif
        @required($required)
        @if ($hasError) aria-invalid="true" @endif
        @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif
    >{{ old($key, $value) }}</textarea>
</x-admin.form.field>
