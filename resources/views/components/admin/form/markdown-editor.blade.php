{{--
    Textarea with a small formatting toolbar that inserts Markdown syntax.
    Content is rendered on the website with raw HTML escaped (see MarkdownRenderer).
--}}
@props([
    'name',
    'label',
    'value' => null,
    'rows' => 12,
    'maxlength' => null,
    'hint' => 'Formatting: ## Heading, **bold**, *italic*, - list item, [link text](https://…). Leave a blank line between paragraphs.',
])

@php
    $id = 'field-'.str_replace(['_', '.'], '-', $name);
    $hasError = $errors->has($name);
    $describedBy = collect([$hint ? $id.'-hint' : null, $hasError ? $id.'-error' : null])->filter()->implode(' ');
    $tools = [
        'heading' => 'Heading',
        'bold' => 'Bold',
        'italic' => 'Italic',
        'list' => 'Bulleted list',
        'link' => 'Link',
    ];
@endphp

<x-admin.form.field :$name :$label :$id :$hint :$maxlength :counter="(bool) $maxlength">
    <div class="editor" data-markdown-editor>
        <div class="editor__toolbar" role="toolbar" aria-label="Formatting" aria-controls="{{ $id }}">
            @foreach ($tools as $action => $toolLabel)
                <button type="button" class="editor__tool" data-md-action="{{ $action }}" title="{{ $toolLabel }}" aria-label="{{ $toolLabel }}">
                    <x-admin.icon :name="$action" />
                </button>
            @endforeach
        </div>
        <textarea
            class="input input--textarea editor__input"
            id="{{ $id }}"
            name="{{ $name }}"
            rows="{{ $rows }}"
            @if ($maxlength) maxlength="{{ $maxlength }}" @endif
            @if ($hasError) aria-invalid="true" @endif
            @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif
            data-md-input
        >{{ old($name, $value) }}</textarea>
    </div>
</x-admin.form.field>
