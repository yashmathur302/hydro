<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\ProductsPageSection;
use App\Http\Requests\Admin\Concerns\HandlesPageSectionImages;
use App\Http\Requests\Admin\Concerns\HasPageBannerFields;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates the Products page editor: banner (banner[...], banner_background_image)
 * and the heading and introduction above the product grid (intro[...]).
 */
final class UpdateProductsPageRequest extends FormRequest
{
    use HandlesPageSectionImages;
    use HasPageBannerFields;

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return $this->bannerRules() + [
            'intro.heading' => ['required', 'string', 'max:120'],
            'intro.text' => ['nullable', 'string', 'max:600'],
        ] + $this->imageRules(ProductsPageSection::cases());
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return $this->bannerAttributes() + ['intro.heading' => 'heading', 'intro.text' => 'introduction'];
    }

    /**
     * @return array<string, string|null>
     */
    public function textFor(ProductsPageSection $section): array
    {
        return $this->validated($section->value, []);
    }
}
