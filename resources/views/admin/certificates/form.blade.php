@php
    use App\Http\Requests\Admin\SaveCertificateRequest as Rules;

    $isEdit = $certificate->exists;
@endphp

<x-admin.layout :title="$isEdit ? 'Edit certificate' : 'Add certificate'">
    <x-slot:header>
        <x-admin.page-header :title="$isEdit ? $certificate->name : 'Add certificate'">
            <x-admin.button :href="route('admin.certificates.index')" variant="secondary">Back to certificates</x-admin.button>
        </x-admin.page-header>
    </x-slot:header>

    <form method="POST" action="{{ $isEdit ? route('admin.certificates.update', $certificate) : route('admin.certificates.store') }}" enctype="multipart/form-data" class="editor-layout" novalidate>
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="editor-layout__main">
            <x-admin.error-summary />

            <x-admin.card title="Details">
                <div class="form-stack">
                    <x-admin.form.input name="name" label="Certificate name" :value="$certificate->name" required maxlength="150" hint="e.g. ISO 9001:2015 Quality Management." />
                    <x-admin.form.input name="issuer" label="Issued by (optional)" :value="$certificate->issuer" maxlength="150" hint="e.g. the certification body or government department." />

                    <div class="form-group mb-0">
                        @if ($isEdit)
                            <a class="file-chip mb-2" href="{{ $certificate->fileUrl() }}" target="_blank" rel="noopener">
                                <x-admin.icon name="file" />
                                <span>Current certificate (PDF)</span>
                                <x-admin.icon name="arrow-up-right" />
                            </a>
                        @endif
                        <label class="form-label" for="field-file">
                            {{ $isEdit ? 'Replace PDF' : 'Certificate PDF' }}
                            @unless ($isEdit)<span class="text-danger" aria-hidden="true">*</span>@endunless
                        </label>
                        <input class="form-control @error('file') is-invalid @enderror" type="file" id="field-file" name="file" accept="application/pdf" data-pdf-cover-source @required(! $isEdit) aria-describedby="field-file-hint @error('file') field-file-error @enderror" @error('file') aria-invalid="true" @enderror>
                        <div class="form-text" id="field-file-hint">PDF up to {{ Rules::PDF_MAX_KILOBYTES / 1024 }} MB. Visitors open it in a new browser tab.</div>
                        @error('file')<div class="invalid-feedback d-block" id="field-file-error">{{ $message }}</div>@enderror
                    </div>

                    {{-- Filled in by resources/js/admin/pdf-cover.js from page 1 of the PDF. --}}
                    <div class="pdf-cover" data-pdf-cover @if ($isEdit && ! $certificate->cover_image_path) data-pdf-url="{{ $certificate->fileUrl() }}" @endif>
                        <p class="form-label mb-2">Cover image</p>
                        <div class="pdf-cover__frame">
                            <img class="pdf-cover__image" src="{{ $certificate->coverUrl() }}" alt="Cover made from the first page of the certificate" data-pdf-cover-image @unless ($certificate->coverUrl()) hidden @endunless>
                            <span class="pdf-cover__placeholder" data-pdf-cover-placeholder @if ($certificate->coverUrl()) hidden @endif><x-admin.icon name="file" /></span>
                        </div>
                        <p class="form-text mb-0" data-pdf-cover-status role="status" aria-live="polite">Made automatically from the first page of the PDF.</p>
                        <input type="file" name="cover_image" accept="image/jpeg" hidden data-pdf-cover-input>
                        @error('cover_image')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>
            </x-admin.card>
        </div>

        <aside class="editor-layout__side">
            <x-admin.card title="Publishing">
                <div class="form-stack">
                    <x-admin.form.toggle name="is_active" label="Show on website" :checked="$certificate->is_active" />
                    <x-admin.form.input name="sort_order" type="number" label="Sort order" :value="$certificate->sort_order ?? 0" min="0" max="100000" hint="Lower numbers appear first. The first {{ \App\Models\Certificate::HOME_LIMIT }} active certificates appear on the home page." />
                </div>
                <x-slot:footer>
                    <x-admin.button type="submit" icon="check" block>{{ $isEdit ? 'Save certificate' : 'Add certificate' }}</x-admin.button>
                </x-slot:footer>
            </x-admin.card>
        </aside>
    </form>
</x-admin.layout>
