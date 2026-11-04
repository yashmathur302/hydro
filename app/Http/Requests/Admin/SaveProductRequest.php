<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Http\Requests\Concerns\ValidatesSeoFields;
use App\Models\Product;
use App\Rules\PdfSignature;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Validator;

/**
 * Validates both creating and updating a product, including gallery images,
 * specification rows and the brochure PDF.
 */
final class SaveProductRequest extends FormRequest
{
    use ValidatesSeoFields;

    public const MAX_IMAGES = 12;

    public const IMAGE_MAX_KILOBYTES = 5120;

    public const BROCHURE_MAX_KILOBYTES = 10240;

    public const MAX_SPECIFICATIONS = 50;

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, list<mixed>>
     */
    public function rules(): array
    {
        return [
            'product_category_id' => ['required', 'integer', 'exists:product_categories,id'],
            'name' => ['required', 'string', 'max:150'],
            'slug' => $this->slugRules('products', $this->currentProduct()?->id),
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string', 'max:20000'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],

            'specifications' => ['nullable', 'array', 'max:'.self::MAX_SPECIFICATIONS],
            'specifications.*.label' => ['nullable', 'string', 'max:100', 'required_with:specifications.*.value'],
            'specifications.*.value' => ['nullable', 'string', 'max:255', 'required_with:specifications.*.label'],

            'images' => ['nullable', 'array', 'max:'.self::MAX_IMAGES],
            'images.*' => ['file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:'.self::IMAGE_MAX_KILOBYTES, 'dimensions:max_width=6000,max_height=6000'],

            'existing_images' => ['nullable', 'array'],
            'existing_images.*.alt_text' => ['nullable', 'string', 'max:150'],
            'existing_images.*.sort_order' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'existing_images.*.remove' => ['nullable', 'boolean'],

            'brochure' => ['nullable', 'file', 'mimes:pdf', 'mimetypes:application/pdf', 'max:'.self::BROCHURE_MAX_KILOBYTES, new PdfSignature],
            'remove_brochure' => ['nullable', 'boolean'],

            ...$this->seoRules(),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'slug.regex' => $this->slugMessage(),
            'specifications.*.label.required_with' => 'Enter a label for this value.',
            'specifications.*.value.required_with' => 'Enter a value for this label.',
            'images.*.image' => 'Each gallery file must be a JPG, PNG or WebP image.',
            'images.*.mimes' => 'Each gallery file must be a JPG, PNG or WebP image.',
            'images.*.max' => 'Each image must be '.(self::IMAGE_MAX_KILOBYTES / 1024).' MB or smaller.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return ['product_category_id' => 'category'];
    }

    /**
     * Keep the total gallery size within the limit once kept + new images are counted.
     *
     * @return list<callable(Validator): void>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $product = $this->currentProduct();
                $removed = collect($this->input('existing_images', []))->filter(static fn ($image): bool => ! empty($image['remove']))->count();
                $kept = $product ? max(0, $product->images()->count() - $removed) : 0;

                if ($kept + count($this->newImages()) > self::MAX_IMAGES) {
                    $validator->errors()->add('images', 'A product can have at most '.self::MAX_IMAGES.' images.');
                }
            },
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function productAttributes(): array
    {
        return [
            ...$this->safe()->only([
                'product_category_id', 'name', 'slug', 'short_description', 'description',
                'meta_title', 'meta_description', 'meta_keywords',
            ]),
            'is_featured' => $this->boolean('is_featured'),
            'is_active' => $this->boolean('is_active'),
            'sort_order' => (int) $this->validated('sort_order', 0),
        ];
    }

    /**
     * Specification rows with empty rows dropped, in submitted order.
     *
     * @return list<array{label: string, value: string}>
     */
    public function specificationRows(): array
    {
        return collect($this->validated('specifications', []))
            ->filter(static fn (array $row): bool => filled($row['label'] ?? null) && filled($row['value'] ?? null))
            ->map(static fn (array $row): array => ['label' => (string) $row['label'], 'value' => (string) $row['value']])
            ->values()
            ->all();
    }

    /**
     * @return array<int, array{alt_text?: string|null, sort_order?: int|null, remove?: bool}>
     */
    public function existingImageChanges(): array
    {
        return collect($this->validated('existing_images', []))
            ->mapWithKeys(static fn (array $change, int|string $id): array => [(int) $id => [
                'alt_text' => $change['alt_text'] ?? null,
                'sort_order' => isset($change['sort_order']) ? (int) $change['sort_order'] : null,
                'remove' => filter_var($change['remove'] ?? false, FILTER_VALIDATE_BOOLEAN),
            ]])
            ->all();
    }

    /**
     * @return list<UploadedFile>
     */
    public function newImages(): array
    {
        $images = $this->file('images', []);

        return array_values(array_filter(is_array($images) ? $images : [$images], static fn ($file): bool => $file instanceof UploadedFile));
    }

    private function currentProduct(): ?Product
    {
        $product = $this->route('product');

        return $product instanceof Product ? $product : null;
    }
}
