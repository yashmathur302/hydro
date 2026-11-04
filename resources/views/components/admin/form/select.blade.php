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
    $id = $attributes->get('id', 'field-'.str_replace(['_', '.'], '-', $name));
    $hasError = $errors->getBag($bag)->has($name);
    $describedBy = collect([$hint ? $id.'-hint' : null, $hasError ? $id.'-error' : null])->filter()->implode(' ');
    $selected = (string) old($name, $value);
@endphp

<x-admin.form.field :$name :$label :$id :$hint :$required :$bag>
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
