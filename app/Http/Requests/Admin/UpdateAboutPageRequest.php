<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\AboutSection;
use App\Http\Requests\Admin\Concerns\HandlesPageSectionImages;
use App\Http\Requests\Admin\Concerns\HasPageBannerFields;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates the About Us page editor. Text fields arrive as section[field]
 * (e.g. banner[title]); images as <section>_<slot> (e.g. banner_background_image).
 */
final class UpdateAboutPageRequest extends FormRequest
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
        return $this->bannerRules() + $this->imageRules(AboutSection::cases());
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return $this->bannerAttributes();
    }

    /**
     * Validated text fields of one section.
     *
     * @return array<string, string|null>
     */
    public function textFor(AboutSection $section): array
    {
        return $this->validated($section->value, []);
    }
}
