<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\FeatureIcon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validates creating and updating an industry card.
 */
final class SaveIndustryRequest extends FormRequest
{
    public const IMAGE_MAX_KILOBYTES = 5120;

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
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:300'],
            'icon' => ['required', Rule::enum(FeatureIcon::class)],
            'image' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:'.self::IMAGE_MAX_KILOBYTES, 'dimensions:max_width=6000,max_height=6000'],
            'remove_image' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function industryAttributes(): array
    {
        return [
            ...$this->safe()->only(['name', 'description', 'icon']),
            'is_active' => $this->boolean('is_active'),
            'sort_order' => (int) $this->validated('sort_order', 0),
        ];
    }
}
