<?php

declare(strict_types=1);

namespace App\Contracts;

/**
 * A fixed, admin-editable section of a website page (implemented by the
 * per-page section enums, e.g. App\Enums\HomeSection). Stored in the
 * page_sections table under page() and the enum value.
 */
interface EditablePageSection extends \BackedEnum
{
    /**
     * The page the section belongs to, e.g. "home" or "about".
     */
    public function page(): string;

    public function label(): string;

    /**
     * Default text content, shown until the admin saves the section.
     *
     * @return array<string, mixed>
     */
    public function defaults(): array;

    /**
     * Image slots with the maximum stored width in pixels.
     *
     * @return array<string, int>
     */
    public function imageSlots(): array;
}
