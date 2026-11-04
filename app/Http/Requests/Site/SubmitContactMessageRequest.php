<?php

declare(strict_types=1);

namespace App\Http\Requests\Site;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Visitor message from the Contact Us page (with the same honeypot as enquiries).
 */
final class SubmitContactMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30', 'regex:/^[0-9+()\-\s]{6,30}$/'],
            'email' => ['nullable', 'string', 'email', 'max:191'],
            'subject' => ['nullable', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:3000'],
            SubmitProductEnquiryRequest::HONEYPOT_FIELD => ['nullable', 'string', 'max:255'],
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
        return filled($this->input(SubmitProductEnquiryRequest::HONEYPOT_FIELD));
    }

    /**
     * @return array<string, string|null>
     */
    public function enquiryFields(): array
    {
        return $this->safe()->only(['name', 'phone', 'email', 'subject', 'message']);
    }
}
