@php
    use App\Enums\LayoutSection;
    use App\Http\Requests\Admin\UpdateLayoutRequest as Rules;

    $maxMb = Rules::IMAGE_MAX_KILOBYTES / 1024;
    $menuRows = old('menu.items', $menu['items'] ?? []);
    $menuRows = $menuRows === [] ? [['label' => '', 'url' => '']] : array_values($menuRows);
@endphp

<x-admin.layout title="Header & Footer">
    <x-slot:header>
        <x-admin.page-header title="Header & Footer" description="The main menu, the call-to-action box above the footer and the footer, shown on every page.">
            <x-admin.button :href="route('home')" variant="secondary" icon="globe" target="_blank" rel="noopener">View website</x-admin.button>
        </x-admin.page-header>
    </x-slot:header>

    <div class="settings">
        <nav class="segmented" aria-label="Header and footer sections">
            <ul class="segmented__list">
                <li><a class="segmented__item" href="#menu" data-section-link>Menu</a></li>
                <li><a class="segmented__item" href="#footer-cta" data-section-link>Footer box</a></li>
                <li><a class="segmented__item" href="#footer" data-section-link>Footer</a></li>
            </ul>
        </nav>

        <form method="POST" action="{{ route('admin.layout.update') }}" enctype="multipart/form-data" class="settings__form" novalidate>
            @csrf
            @method('PUT')

            <x-admin.error-summary />

            <x-admin.card id="menu" title="Menu" description="Links in the header menu, in this order. The footer's first link column shows the same links.">
                <div class="repeater" data-repeater data-repeater-name="menu[items]" data-repeater-id-prefix="menu" data-repeater-max="{{ LayoutSection::MAX_MENU_ITEMS }}">
                    <div class="repeater__head" aria-hidden="true">
                        <span>Menu text</span><span>Link</span><span></span>
                    </div>
                    <ol class="repeater__rows" data-repeater-rows>
                        @foreach ($menuRows as $index => $row)
                            <li class="repeater__row" data-repeater-row>
                                <div class="repeater__cell">
                                    <label class="visually-hidden" for="menu-{{ $index }}-label">Menu text</label>
                                    <input class="form-control form-control-sm @error("menu.items.$index.label") is-invalid @enderror" id="menu-{{ $index }}-label" name="menu[items][{{ $index }}][label]" value="{{ $row['label'] ?? '' }}" maxlength="30" placeholder="e.g. About Us" data-repeater-field="label">
                                    @error("menu.items.$index.label")<p class="invalid-feedback d-block mb-0">{{ $message }}</p>@enderror
                                </div>
                                <div class="repeater__cell">
                                    <label class="visually-hidden" for="menu-{{ $index }}-url">Link</label>
                                    <input class="form-control form-control-sm @error("menu.items.$index.url") is-invalid @enderror" id="menu-{{ $index }}-url" name="menu[items][{{ $index }}][url]" value="{{ $row['url'] ?? '' }}" maxlength="255" placeholder="e.g. /about-us" data-repeater-field="url">
                                    @error("menu.items.$index.url")<p class="invalid-feedback d-block mb-0">{{ $message }}</p>@enderror
                                </div>
                                <button type="button" class="btn btn-sm btn-icon btn-soft-danger" data-repeater-remove aria-label="Remove this menu item" title="Remove">
                                    <x-admin.icon name="trash" />
                                </button>
                            </li>
                        @endforeach
                    </ol>
                    @error('menu.items')<p class="invalid-feedback d-block">{{ $message }}</p>@enderror
                    <x-admin.button variant="secondary" size="small" icon="plus" data-repeater-add>Add menu item</x-admin.button>
                </div>
                <p class="form-text mb-0 mt-3">
                    Links: a page path such as <code>/about-us</code>, <code>/products</code>, <code>/certificates</code>, <code>/contact-us</code> or a static page like <code>/privacy-policy</code>; or a full https:// address (opens in a new tab). Up to {{ LayoutSection::MAX_MENU_ITEMS }} items.
                </p>
            </x-admin.card>

            <x-admin.card id="footer-cta" title="Footer box" description="The box that overlaps the top of the footer, with a button to the contact page. The phone and email come from Site settings › Contact.">
                <div class="form-stack">
                    <div class="form-grid">
                        <x-admin.form.input name="footer_cta[eyebrow]" label="Small text above the heading" :value="$cta['eyebrow']" maxlength="80" />
                        <x-admin.form.input name="footer_cta[heading]" label="Heading" :value="$cta['heading']" required maxlength="120" />
                        <x-admin.form.input name="footer_cta[button_label]" label="Button text" :value="$cta['button_label']" maxlength="40" hint="Leave empty to hide the button." />
                        <x-admin.form.input name="footer_cta[button_url]" label="Button link" :value="$cta['button_url']" maxlength="255" hint="e.g. /contact-us" />
                    </div>
                    <x-admin.form.image-upload name="footer_cta_background_image" label="Photo" :current-url="$cta['background_image_url']" accept="image/jpeg,image/png,image/webp" remove-name="remove_footer_cta_background_image" hint="Shown on the right side of the box, fading into the dark background. Landscape, at least 1200 px wide. Up to {{ $maxMb }} MB." />
                </div>
            </x-admin.card>

            <x-admin.card id="footer" title="Footer" description="Column texts and the copyright line. Useful Links lists the active Static Pages; contact details and social icons come from Site settings.">
                <div class="form-stack">
                    <x-admin.form.image-upload name="footer_logo" label="Footer logo (optional)" :current-url="$footer['logo_url']" accept="image/png,image/webp" remove-name="remove_footer_logo" hint="A light version of the logo for the dark footer, PNG with a transparent background. Without one, the main logo is used." />
                    <x-admin.form.textarea name="footer[description]" label="Text under the logo" :value="$footer['description']" maxlength="400" counter rows="3" />
                    <div class="form-grid">
                        <x-admin.form.input name="footer[menu_heading]" label="Menu links heading" :value="$footer['menu_heading']" maxlength="40" />
                        <x-admin.form.input name="footer[links_heading]" label="Useful links heading" :value="$footer['links_heading']" maxlength="40" />
                        <x-admin.form.input name="footer[contact_heading]" label="Contact heading" :value="$footer['contact_heading']" maxlength="40" />
                    </div>
                    <x-admin.form.input name="footer[copyright]" label="Copyright line" :value="$footer['copyright']" maxlength="200" hint="Write {year} to show the current year automatically." />
                </div>
            </x-admin.card>

            <div class="savebar">
                <x-admin.icon name="globe" class="savebar__icon" />
                <p class="savebar__text">Changes apply to every page of the website as soon as you save.</p>
                <x-admin.button type="submit" icon="check">Save header &amp; footer</x-admin.button>
            </div>
        </form>
    </div>
</x-admin.layout>
