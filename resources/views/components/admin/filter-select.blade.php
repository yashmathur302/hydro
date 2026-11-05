@props(['name', 'label', 'options', 'value' => null, 'placeholder'])

<div class="select-wrap filters__select">
    <label class="visually-hidden" for="filter-{{ $name }}">{{ $label }}</label>
    <select class="input input--select" id="filter-{{ $name }}" name="{{ $name }}">
        <option value="">{{ $placeholder }}</option>
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected((string) $value === (string) $optionValue)>{{ $optionLabel }}</option>
        @endforeach
    </select>
    <x-admin.icon name="chevron-down" class="select-wrap__icon" />
</div>
