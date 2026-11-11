<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Concerns;

/**
 * Text fields of the page banner (small text and title) shared by the inner
 * page editors. The background photo is an image slot of the Banner section.
 */
trait HasPageBannerFields
{
    /**
     * @return array<string, list<string>>
     */
    protected function bannerRules(): array
    {
        return [
            'banner.eyebrow' => ['nullable', 'string', 'max:80'],
            'banner.title' => ['required', 'string', 'max:80'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function bannerAttributes(): array
    {
        return [
            'banner.eyebrow' => 'small text above the title',
            'banner.title' => 'page title',
            'banner_background_image' => 'background photo',
        ];
    }
}
