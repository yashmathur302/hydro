<?php

declare(strict_types=1);

namespace App\Http\Requests\Site;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Visitor enquiry about a product. The hidden "website" field is a honeypot:
 * people never see it, so a value there means the form was filled by a bot.
 */
final class SubmitProductEnquiryRequest extends FormRequest
{
    public const HONEYPOT_FIELD = 'website';

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<mixed>>
     */
    public function rules(): array
    {
        return [
            'product' => ['required', 'string', 'max:170', Rule::exists('products', 'slug')->where('is_active', true)],
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30', 'regex:/^[0-9+()\-\s]{6,30}$/'],
            'email' => ['nullable', 'string', 'email', 'max:191'],
            'company' => ['nullable', 'string', 'max:150'],
            'city' => ['nullable', 'string', 'max:100'],
            'message' => ['nullable', 'string', 'max:3000'],
            self::HONEYPOT_FIELD => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return ['phone.regex' => 'Enter a valid phone number.'];
    }

    public function isSpam(): bool
    {
        return filled($this->input(self::HONEYPOT_FIELD));
    }

    public function product(): Product
    {
        return Product::query()->active()->where('slug', $this->validated('product'))->firstOrFail();
    }

    /**
     * @return array<string, string|null>
     */
    public function enquiryFields(): array
    {
        return $this->safe()->only(['name', 'phone', 'email', 'company', 'city', 'message']);
    }
}
