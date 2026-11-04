<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Http\Requests\Concerns\ValidatesSeoFields;
use App\Models\Page;
use Illuminate\Foundation\Http\FormRequest;

final class SavePageRequest extends FormRequest
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
        $page = $this->route('page');

        return [
            'title' => ['required', 'string', 'max:150'],
            'slug' => $this->slugRules('pages', $page instanceof Page ? $page->id : null),
            'content' => ['nullable', 'string', 'max:100000'],
            'is_active' => ['nullable', 'boolean'],
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
    public function pageAttributes(): array
    {
        return [
            ...$this->safe()->only(['title', 'slug', 'content', 'meta_title', 'meta_description', 'meta_keywords']),
            'is_active' => $this->boolean('is_active'),
        ];
    }
}
