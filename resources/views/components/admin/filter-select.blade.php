@props(['name', 'label', 'options', 'value' => null, 'placeholder'])

<div class="filters__select">
    <label class="visually-hidden" for="filter-{{ $name }}">{{ $label }}</label>
    <select class="form-select" id="filter-{{ $name }}" name="{{ $name }}" data-auto-submit>
        <option value="">{{ $placeholder }}</option>
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected((string) $value === (string) $optionValue)>{{ $optionLabel }}</option>
        @endforeach
    </select>
</div>
