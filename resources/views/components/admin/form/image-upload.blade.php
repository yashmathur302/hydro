{{-- Image upload with a live preview of the current or newly chosen file and an optional remove checkbox. --}}
@props(['name', 'label', 'currentUrl' => null, 'accept', 'hint' => null, 'removeName' => null])

@php
    $id = 'field-'.str_replace('_', '-', $name);
    $error = $errors->first($name);
    $describedBy = collect([$hint ? $id.'-hint' : null, $error ? $id.'-error' : null])->filter()->implode(' ');
@endphp

<div class="form-group mb-0 upload" data-image-upload>
    <span class="form-label d-block" id="{{ $id }}-label">{{ $label }}</span>

    <div class="d-flex flex-wrap align-items-center gap-3">
        <div class="upload__preview" data-preview-frame @if (! $currentUrl) data-empty @endif>
            <img src="{{ $currentUrl }}" alt="" data-preview-image @if (! $currentUrl) hidden @endif>
            <span class="upload__placeholder" data-preview-placeholder @if ($currentUrl) hidden @endif>No file</span>
        </div>

        <div class="d-grid gap-2 justify-items-start">
            <label class="btn btn-outline-primary btn-sm upload__button" for="{{ $id }}">{{ $currentUrl ? 'Replace file' : 'Choose file' }}</label>
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
            <span class="form-text mt-0 upload__filename" data-preview-filename aria-live="polite"></span>

            @if ($currentUrl && $removeName)
                <div class="form-check mb-0">
                    <input class="form-check-input" type="checkbox" id="{{ $id }}-remove" name="{{ $removeName }}" value="1">
                    <label class="form-check-label" for="{{ $id }}-remove">Remove current file</label>
                </div>
            @endif
        </div>
    </div>

    @if ($hint)
        <div class="form-text" id="{{ $id }}-hint">{{ $hint }}</div>
    @endif
    @if ($error)
        <div class="invalid-feedback d-block" id="{{ $id }}-error">{{ $error }}</div>
    @endif
</div>
