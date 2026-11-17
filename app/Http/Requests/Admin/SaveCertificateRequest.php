<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Certificate;
use App\Rules\PdfSignature;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates creating and updating a certificate. The PDF is required for a
 * new certificate and optional (keep current) when editing; the cover image
 * is always optional.
 */
final class SaveCertificateRequest extends FormRequest
{
    public const PDF_MAX_KILOBYTES = 10240;

    public const COVER_MAX_KILOBYTES = 2048;

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, list<mixed>>
     */
    public function rules(): array
    {
        $isNew = ! $this->route('certificate') instanceof Certificate;

        return [
            'name' => ['required', 'string', 'max:150'],
            'issuer' => ['nullable', 'string', 'max:150'],
            'file' => [$isNew ? 'required' : 'nullable', 'file', 'mimes:pdf', 'mimetypes:application/pdf', 'max:'.self::PDF_MAX_KILOBYTES, new PdfSignature],
            'cover_image' => ['nullable', 'file', 'image', 'mimes:png,jpg,jpeg,webp', 'max:'.self::COVER_MAX_KILOBYTES, 'dimensions:max_width=6000,max_height=6000'],
            'remove_cover_image' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'certificate name',
            'issuer' => 'issued by',
            'file' => 'certificate PDF',
            'cover_image' => 'cover image',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function certificateAttributes(): array
    {
        return [
            ...$this->safe()->only(['name', 'issuer']),
            'is_active' => $this->boolean('is_active'),
            'sort_order' => (int) $this->validated('sort_order', 0),
        ];
    }
}
