<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Client;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates creating and updating a client logo. The logo is required for a
 * new client and optional (keep current) when editing.
 */
final class SaveClientRequest extends FormRequest
{
    public const LOGO_MAX_KILOBYTES = 2048;

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        $isNew = ! $this->route('client') instanceof Client;

        return [
            'name' => ['required', 'string', 'max:100'],
            'logo' => [$isNew ? 'required' : 'nullable', 'file', 'image', 'mimes:png,jpg,jpeg,webp', 'max:'.self::LOGO_MAX_KILOBYTES, 'dimensions:max_width=4000,max_height=4000'],
            'website_url' => ['nullable', 'string', 'url:http,https', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function clientAttributes(): array
    {
        return [
            ...$this->safe()->only(['name', 'website_url']),
            'is_active' => $this->boolean('is_active'),
            'sort_order' => (int) $this->validated('sort_order', 0),
        ];
    }
}
