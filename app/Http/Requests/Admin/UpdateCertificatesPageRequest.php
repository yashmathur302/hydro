<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\CertificatesPageSection;
use App\Http\Requests\Admin\Concerns\HandlesPageSectionImages;
use App\Http\Requests\Admin\Concerns\HasPageBannerFields;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates the Certificates page banner (banner[eyebrow], banner[title] and
 * banner_background_image).
 */
final class UpdateCertificatesPageRequest extends FormRequest
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
        return $this->bannerRules() + $this->imageRules(CertificatesPageSection::cases());
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return $this->bannerAttributes();
    }

    /**
     * @return array<string, string|null>
     */
    public function textFor(CertificatesPageSection $section): array
    {
        return $this->validated($section->value, []);
    }
}
