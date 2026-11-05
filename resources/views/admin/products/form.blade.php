@php
    use App\Http\Requests\Admin\SaveProductRequest as Rules;

    $isEdit = $product->exists;
    $specifications = old('specifications', $product->exists ? $product->specifications->map->only(['label', 'value'])->all() : []);
    $specifications = $specifications === [] ? [['label' => '', 'value' => '']] : array_values($specifications);
@endphp

<x-admin.layout :title="$isEdit ? 'Edit product' : 'Add product'">
    <x-admin.page-header :title="$isEdit ? $product->name : 'Add product'" :description="$isEdit ? 'Last updated '.$product->updated_at->diffForHumans().'.' : 'Fill in the details shown on the product page.'">
        <x-admin.button :href="route('admin.products.index')" variant="secondary">Back to products</x-admin.button>
    </x-admin.page-header>

    <form method="POST" action="{{ $isEdit ? route('admin.products.update', $product) : route('admin.products.store') }}" enctype="multipart/form-data" class="editor-layout" novalidate>
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="editor-layout__main">
            <x-admin.error-summary />

            <x-admin.card title="Details">
                <div class="form-stack">
                    <x-admin.form.input name="name" label="Product name" :value="$product->name" required maxlength="150" />
                    <x-admin.form.textarea name="short_description" label="Short description" :value="$product->short_description" maxlength="500" counter rows="3" hint="One or two sentences shown on product cards and under the title." />
                    <x-admin.form.markdown-editor name="description" label="Full description" :value="$product->description" maxlength="20000" />
                </div>
            </x-admin.card>

            <x-admin.card title="Images" description="JPG, PNG or WebP, up to {{ Rules::IMAGE_MAX_KILOBYTES / 1024 }} MB each, {{ Rules::MAX_IMAGES }} images at most. They are converted to WebP automatically. The first image is the main one.">
                @error('images')<p class="field__error field__error--block">{{ $message }}</p>@enderror
                @foreach ($errors->get('images.*') as $messages)
                    <p class="field__error field__error--block">{{ $messages[0] }}</p>
                @endforeach

                @if ($isEdit && $product->images->isNotEmpty())
                    <ul class="gallery">
                        @foreach ($product->images as $image)
                            <li class="gallery__item">
                                <img class="gallery__image" src="{{ $image->thumbnailUrl() }}" alt="" width="240" height="180" loading="lazy">
                                <div class="gallery__fields">
                                    <label class="visually-hidden" for="image-{{ $image->id }}-alt">Alt text for image {{ $loop->iteration }}</label>
                                    <input class="input input--compact" id="image-{{ $image->id }}-alt" name="existing_images[{{ $image->id }}][alt_text]" value="{{ old("existing_images.{$image->id}.alt_text", $image->alt_text) }}" maxlength="150" placeholder="Alt text">
                                    <div class="gallery__row">
                                        <label class="gallery__order">
                                            <span>Order</span>
                                            <input class="input input--compact" type="number" min="0" max="1000" name="existing_images[{{ $image->id }}][sort_order]" value="{{ old("existing_images.{$image->id}.sort_order", $image->sort_order) }}">
                                        </label>
                                        <label class="checkbox">
                                            <input type="checkbox" name="existing_images[{{ $image->id }}][remove]" value="1" @checked(old("existing_images.{$image->id}.remove"))>
                                            <span>Remove</span>
                                        </label>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <div class="dropzone" data-multi-upload>
                    <input class="visually-hidden" type="file" id="field-images" name="images[]" accept="image/jpeg,image/png,image/webp" multiple data-multi-upload-input>
                    <label class="dropzone__label" for="field-images">
                        <x-admin.icon name="image" />
                        <span><strong>Choose images</strong> or drag them here</span>
                    </label>
                    <ul class="dropzone__previews" data-multi-upload-previews aria-live="polite"></ul>
                </div>
            </x-admin.card>

            <x-admin.card title="Specifications" description="Shown as a table on the product page, in this order. Empty rows are ignored.">
                <div class="repeater" data-repeater data-repeater-max="{{ Rules::MAX_SPECIFICATIONS }}">
                    <div class="repeater__head" aria-hidden="true">
                        <span>Label</span><span>Value</span><span></span>
                    </div>
                    <ol class="repeater__rows" data-repeater-rows>
                        @foreach ($specifications as $index => $row)
                            <li class="repeater__row" data-repeater-row>
                                <div class="repeater__cell">
                                    <label class="visually-hidden" for="spec-{{ $index }}-label">Specification label</label>
                                    <input class="input input--compact @error("specifications.$index.label") is-invalid @enderror" id="spec-{{ $index }}-label" name="specifications[{{ $index }}][label]" value="{{ $row['label'] ?? '' }}" maxlength="100" placeholder="e.g. Material" data-repeater-field="label">
                                    @error("specifications.$index.label")<p class="field__error">{{ $message }}</p>@enderror
                                </div>
                                <div class="repeater__cell">
                                    <label class="visually-hidden" for="spec-{{ $index }}-value">Specification value</label>
                                    <input class="input input--compact @error("specifications.$index.value") is-invalid @enderror" id="spec-{{ $index }}-value" name="specifications[{{ $index }}][value]" value="{{ $row['value'] ?? '' }}" maxlength="255" placeholder="e.g. Stainless steel 316" data-repeater-field="value">
                                    @error("specifications.$index.value")<p class="field__error">{{ $message }}</p>@enderror
                                </div>
                                <button type="button" class="icon-action icon-action--danger" data-repeater-remove aria-label="Remove this row" title="Remove row">
                                    <x-admin.icon name="trash" />
                                </button>
                            </li>
                        @endforeach
                    </ol>
                    <x-admin.button variant="secondary" size="small" icon="plus" data-repeater-add>Add row</x-admin.button>
                </div>
            </x-admin.card>

            <x-admin.seo-fields :model="$product" :url-prefix="url('/products').'/'" />
        </div>

        <aside class="editor-layout__side">
            <x-admin.card title="Publishing">
                <div class="form-stack">
                    <x-admin.form.select name="product_category_id" label="Category" :options="$categories" :value="$product->product_category_id" placeholder="Choose a category" required />
                    <x-admin.form.toggle name="is_active" label="Show on website" :checked="$product->is_active" hint="Hidden products cannot be viewed or enquired about." />
                    <x-admin.form.toggle name="is_featured" label="Featured" :checked="$product->is_featured" hint="Highlight on the home page." />
                    <x-admin.form.input name="sort_order" type="number" label="Sort order" :value="$product->sort_order ?? 0" min="0" max="100000" hint="Lower numbers appear first." />
                </div>

                <x-slot:footer>
                    <x-admin.button type="submit" icon="check" block>{{ $isEdit ? 'Save product' : 'Create product' }}</x-admin.button>
                </x-slot:footer>
            </x-admin.card>

            <x-admin.card title="Brochure" description="Optional PDF, up to {{ Rules::BROCHURE_MAX_KILOBYTES / 1024 }} MB.">
                <div class="form-stack">
                    @if ($product->brochure_path)
                        <a class="file-chip" href="{{ Storage::disk('public')->url($product->brochure_path) }}" target="_blank" rel="noopener">
                            <x-admin.icon name="file" />
                            <span>Current brochure (PDF)</span>
                            <x-admin.icon name="arrow-up-right" />
                        </a>
                        <label class="checkbox">
                            <input type="checkbox" name="remove_brochure" value="1">
                            <span>Remove brochure</span>
                        </label>
                    @endif
                    <div @class(['field', 'has-error' => $errors->has('brochure')])>
                        <label class="field__label" for="field-brochure">{{ $product->brochure_path ? 'Replace PDF' : 'Upload PDF' }}</label>
                        <input class="file-input" type="file" id="field-brochure" name="brochure" accept="application/pdf" @error('brochure') aria-invalid="true" aria-describedby="field-brochure-error" @enderror>
                        @error('brochure')<p class="field__error" id="field-brochure-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </x-admin.card>

            @if ($isEdit)
                <x-admin.card title="Danger zone">
                    <p class="panel__note">Deleting removes the product, its images and its brochure. Past enquiries are kept.</p>
                    <x-slot:footer>
                        <x-admin.button type="submit" variant="danger" icon="trash" form="delete-product-form">Delete product</x-admin.button>
                    </x-slot:footer>
                </x-admin.card>
            @endif
        </aside>
    </form>

    @if ($isEdit)
        {{-- Kept outside the main form (forms cannot be nested); the button above targets it by id. --}}
        <form id="delete-product-form" method="POST" action="{{ route('admin.products.destroy', $product) }}" data-confirm="Delete &quot;{{ $product->name }}&quot;? This cannot be undone.">
            @csrf
            @method('DELETE')
        </form>
    @endif
</x-admin.layout>
