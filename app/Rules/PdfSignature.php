<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

/**
 * Checks the file really is a PDF by reading its first bytes ("%PDF-"),
 * instead of trusting the file name or the browser-supplied type.
 */
final class PdfSignature implements ValidationRule
{
    private const SIGNATURE = '%PDF-';

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $value instanceof UploadedFile || ! $value->isValid()) {
            return;
        }

        $handle = fopen($value->getRealPath(), 'rb');
        $header = $handle === false ? '' : (string) fread($handle, strlen(self::SIGNATURE));

        if ($handle !== false) {
            fclose($handle);
        }

        if ($header !== self::SIGNATURE) {
            $fail('The :attribute must be a real PDF file.');
        }
    }
}
