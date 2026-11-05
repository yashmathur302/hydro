@props([
    'name',
    'label',
    'options',
    'value' => null,
    'placeholder' => null,
    'hint' => null,
    'required' => false,
    'bag' => 'default',
])

@php
    $key = trim(str_replace(['[', ']'], ['.', ''], $name), '.');
    $id = $attributes->get('id', 'field-'.str_replace(['_', '.'], '-', $key));
    $hasError = $errors->getBag($bag)->has($key);
    $describedBy = collect([$hint ? $id.'-hint' : null, $hasError ? $id.'-error' : null])->filter()->implode(' ');
    $selected = (string) old($key, $value);
@endphp

<x-admin.form.field :name="$key" :$label :$id :$hint :$required :$bag>
    <div class="select-wrap">
        <select
            {{ $attributes->except('id')->class(['input', 'input--select']) }}
            id="{{ $id }}"
            name="{{ $name }}"
            @required($required)
            @if ($hasError) aria-invalid="true" @endif
            @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif
        >
            @if ($placeholder !== null)
                <option value="">{{ $placeholder }}</option>
            @endif
            @foreach ($options as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" @selected($selected === (string) $optionValue)>{{ $optionLabel }}</option>
            @endforeach
        </select>
        <x-admin.icon name="chevron-down" class="select-wrap__icon" />
    </div>
</x-admin.form.field>
