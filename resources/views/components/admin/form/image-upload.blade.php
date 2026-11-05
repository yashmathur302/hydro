{{-- Image upload with a live preview of the current or newly selected file and an optional remove checkbox. --}}
@props(['name', 'label', 'currentUrl' => null, 'accept', 'hint' => null, 'removeName' => null])

@php
    $id = 'field-'.str_replace('_', '-', $name);
    $error = $errors->first($name);
    $describedBy = collect([$hint ? $id.'-hint' : null, $error ? $id.'-error' : null])->filter()->implode(' ');
@endphp

<div @class(['field', 'upload', 'has-error' => $error]) data-image-upload>
    <span class="field__label" id="{{ $id }}-label">{{ $label }}</span>

    <div class="upload__body">
        <div class="upload__preview" data-preview-frame @if (! $currentUrl) data-empty @endif>
            <img src="{{ $currentUrl }}" alt="" data-preview-image @if (! $currentUrl) hidden @endif>
            <span class="upload__placeholder" data-preview-placeholder @if ($currentUrl) hidden @endif>No file</span>
        </div>

        <div class="upload__controls">
            <label class="button button--secondary upload__button" for="{{ $id }}">
                <span>{{ $currentUrl ? 'Replace file' : 'Choose file' }}</span>
            </label>
            <input
                class="visually-hidden"
                type="file"
                id="{{ $id }}"
                name="{{ $name }}"
                accept="{{ $accept }}"
                aria-labelledby="{{ $id }}-label"
                @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif
                @if ($error) aria-invalid="true" @endif
                data-preview-input
            >
            <span class="upload__filename" data-preview-filename aria-live="polite"></span>

            @if ($currentUrl && $removeName)
                <label class="checkbox">
                    <input type="checkbox" name="{{ $removeName }}" value="1">
                    <span>Remove current file</span>
                </label>
            @endif
        </div>
    </div>

    @if ($hint)
        <p class="field__hint" id="{{ $id }}-hint">{{ $hint }}</p>
    @endif
    @if ($error)
        <p class="field__error" id="{{ $id }}-error">{{ $error }}</p>
    @endif
</div>
