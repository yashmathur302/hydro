<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Concerns;

use App\Contracts\EditablePageSection;
use Illuminate\Http\UploadedFile;

/**
 * Image fields of the page editors: each image slot arrives as
 * <section>_<slot> (e.g. hero_background_image), with a matching
 * remove_<section>_<slot> checkbox to clear it.
 */
trait HandlesPageSectionImages
{
    public const IMAGE_MAX_KILOBYTES = 5120;

    /**
     * @param  iterable<EditablePageSection>  $sections
     * @return array<string, list<string>>
     */
    protected function imageRules(iterable $sections): array
    {
        $rules = [];

        foreach ($sections as $section) {
            foreach (array_keys($section->imageSlots()) as $slot) {
                $field = $section->value.'_'.$slot;
                $rules[$field] = ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:'.self::IMAGE_MAX_KILOBYTES, 'dimensions:max_width=6000,max_height=6000'];
                $rules['remove_'.$field] = ['nullable', 'boolean'];
            }
        }

        return $rules;
    }

    /**
     * @return array<string, UploadedFile>
     */
    public function uploadsFor(EditablePageSection $section): array
    {
        $uploads = [];

        foreach (array_keys($section->imageSlots()) as $slot) {
            $file = $this->file($section->value.'_'.$slot);

            if ($file instanceof UploadedFile) {
                $uploads[$slot] = $file;
            }
        }

        return $uploads;
    }

    /**
     * @return list<string>
     */
    public function removalsFor(EditablePageSection $section): array
    {
        return array_values(array_filter(
            array_keys($section->imageSlots()),
            fn (string $slot): bool => $this->boolean('remove_'.$section->value.'_'.$slot),
        ));
    }
}
