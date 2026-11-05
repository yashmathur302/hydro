<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Http\Requests\Concerns\ValidatesSeoFields;
use App\Models\ProductCategory;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates both creating and updating a product category.
 */
final class SaveProductCategoryRequest extends FormRequest
{
    use ValidatesSeoFields;

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, list<mixed>>
     */
    public function rules(): array
    {
        $category = $this->route('category');

        return [
            'name' => ['required', 'string', 'max:150'],
            'slug' => $this->slugRules('product_categories', $category instanceof ProductCategory ? $category->id : null),
            'description' => ['nullable', 'string', 'max:2000'],
            'image' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120', 'dimensions:max_width=6000,max_height=6000'],
            'remove_image' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
            ...$this->seoRules(),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return ['slug.regex' => $this->slugMessage()];
    }

    /**
     * @return array<string, mixed>
     */
    public function categoryAttributes(): array
    {
        return [
            ...$this->safe()->only(['name', 'slug', 'description', 'meta_title', 'meta_description', 'meta_keywords']),
            'is_active' => $this->boolean('is_active'),
            'sort_order' => (int) $this->validated('sort_order', 0),
        ];
    }
}
