<?php

declare(strict_types=1);

namespace App\Http\Requests\Concerns;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

/**
 * Shared rules for the slug and SEO fields used by products, categories and pages.
 */
trait ValidatesSeoFields
{
    public const SLUG_PATTERN = '/^[a-z0-9]+(?:-[a-z0-9]+)*$/';

    /**
     * @return array<string, list<string>>
     */
    protected function seoRules(): array
    {
        return [
            'meta_title' => ['nullable', 'string', 'max:120'],
            'meta_description' => ['nullable', 'string', 'max:300'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * @return list<string|Unique>
     */
    protected function slugRules(string $table, ?int $ignoreId): array
    {
        return [
            'nullable',
            'string',
            'max:170',
            'regex:'.self::SLUG_PATTERN,
            Rule::unique($table, 'slug')->ignore($ignoreId),
        ];
    }

    protected function slugMessage(): string
    {
        return 'Use lower-case letters, numbers and single hyphens only, e.g. "stainless-steel-valve".';
    }
}
