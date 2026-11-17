@php
    use App\Enums\HomeSection;
    use App\Http\Requests\Admin\UpdateHomePageRequest as Rules;

    $maxMb = Rules::IMAGE_MAX_KILOBYTES / 1024;
    $points = array_pad(old('about.points', $about['points'] ?? []), HomeSection::MAX_ABOUT_POINTS, '');
    $statRows = array_pad(old('stats.items', $stats['items'] ?? []), HomeSection::STAT_COUNT, ['value' => '', 'label' => '']);
    $whyRows = array_pad(old('why_us.items', $whyUs['items'] ?? []), HomeSection::MAX_WHY_ITEMS, ['icon' => '', 'title' => '']);
    $iconOptions = \App\Enums\FeatureIcon::options();
@endphp

<x-admin.layout title="Home page">
    <x-slot:header>
        <x-admin.page-header title="Home page" description="Text and images of the home page sections. Contact details in the header come from Site settings.">
            <x-admin.button :href="route('home')" variant="secondary" icon="globe" target="_blank" rel="noopener">View home page</x-admin.button>
        </x-admin.page-header>
    </x-slot:header>

    <div class="settings">
        <nav class="segmented" aria-label="Home page sections">
            <ul class="segmented__list">
                <li><a class="segmented__item" href="#banner" data-section-link>Banner</a></li>
                <li><a class="segmented__item" href="#about" data-section-link>About us</a></li>
                <li><a class="segmented__item" href="#products" data-section-link>Products</a></li>
                <li><a class="segmented__item" href="#industries" data-section-link>Industries</a></li>
                <li><a class="segmented__item" href="#counters" data-section-link>Counters</a></li>
                <li><a class="segmented__item" href="#why-us" data-section-link>Why choose us</a></li>
                <li><a class="segmented__item" href="#certificates" data-section-link>Certificates</a></li>
            </ul>
        </nav>

        <form method="POST" action="{{ route('admin.home-page.update') }}" enctype="multipart/form-data" class="settings__form" novalidate>
            @csrf
            @method('PUT')

            <x-admin.error-summary />

            <x-admin.card id="banner" title="Banner" description="The large first section with the background photo.">
                <div class="form-stack">
                    <x-admin.form.input name="hero[eyebrow]" label="Small text above the heading" :value="$hero['eyebrow']" maxlength="80" />
                    <x-admin.form.input name="hero[heading]" label="Heading" :value="$hero['heading']" required maxlength="120" counter />
                    <x-admin.form.textarea name="hero[text]" label="Text" :value="$hero['text']" maxlength="400" counter rows="3" />
                    <div class="form-grid">
                        <x-admin.form.input name="hero[button_label]" label="Button text" :value="$hero['button_label']" maxlength="40" />
                        <x-admin.form.input name="hero[button_url]" label="Button link" :value="$hero['button_url']" maxlength="255" hint="A page path such as /products, a full https:// link, or # for now." />
                    </div>
                    <x-admin.form.image-upload name="hero_background_image" label="Background photo" :current-url="$hero['background_image_url']" accept="image/jpeg,image/png,image/webp" remove-name="remove_hero_background_image" hint="Wide landscape photo, at least 1920 × 900 px. JPG, PNG or WebP up to {{ $maxMb }} MB." />
                    <p class="text-secondary mb-0">The phone number next to the button comes from Site settings › Contact.</p>
                </div>
            </x-admin.card>

            <x-admin.card id="about" title="About us" description="The section right after the banner.">
                <div class="form-stack">
                    <div class="form-grid">
                        <x-admin.form.input name="about[eyebrow]" label="Small text above the heading" :value="$about['eyebrow']" maxlength="80" />
                        <x-admin.form.input name="about[heading]" label="Heading" :value="$about['heading']" required maxlength="120" />
                    </div>
                    <x-admin.form.textarea name="about[intro]" label="Introduction" :value="$about['intro']" maxlength="600" counter rows="3" />
                    <x-admin.form.input name="about[subheading]" label="Bold line" :value="$about['subheading']" maxlength="160" />

                    <fieldset class="fieldset">
                        <legend class="form-label">Check-list points</legend>
                        <div class="form-stack form-stack--tight">
                            @foreach ($points as $index => $point)
                                <x-admin.form.input name="about[points][{{ $index }}]" label="Point {{ $index + 1 }}" :value="$point" maxlength="120" />
                            @endforeach
                        </div>
                        <p class="form-text mb-0">Empty points are not shown.</p>
                    </fieldset>

                    <div class="form-grid">
                        <x-admin.form.input name="about[button_label]" label="Button text" :value="$about['button_label']" maxlength="40" />
                        <x-admin.form.input name="about[button_url]" label="Button link" :value="$about['button_url']" maxlength="255" />
                    </div>

                    <x-admin.form.image-upload name="about_image_main" label="Main photo (tall)" :current-url="$about['image_main_url']" accept="image/jpeg,image/png,image/webp" remove-name="remove_about_image_main" hint="Portrait, about 620 × 740 px." />
                    <x-admin.form.image-upload name="about_image_top" label="Small photo (top right)" :current-url="$about['image_top_url']" accept="image/jpeg,image/png,image/webp" remove-name="remove_about_image_top" hint="About 330 × 360 px." />
                    <x-admin.form.image-upload name="about_image_bottom" label="Photo (bottom)" :current-url="$about['image_bottom_url']" accept="image/jpeg,image/png,image/webp" remove-name="remove_about_image_bottom" hint="Landscape, about 500 × 420 px." />
                    <x-admin.form.image-upload name="about_decoration_image" label="Side image (bottom right)" :current-url="$about['decoration_image_url']" accept="image/png,image/webp" remove-name="remove_about_decoration_image" hint="Optional cut-out product photo with transparent background (PNG). Shown on large screens." />
                </div>
            </x-admin.card>

            <x-admin.card id="products" title="Products" description="Shows up to {{ \App\Models\Product::HOME_LIMIT }} active products: those marked Featured first, then the rest in catalogue order. The section is hidden while there are none.">
                <div class="form-stack">
                    <div class="form-grid">
                        <x-admin.form.input name="products[eyebrow]" label="Small text above the heading" :value="$products['eyebrow']" maxlength="80" />
                        <x-admin.form.input name="products[heading]" label="Heading" :value="$products['heading']" required maxlength="120" />
                    </div>
                    <x-admin.form.input name="products[link_label]" label="Button text" :value="$products['link_label']" maxlength="40" hint="Button to the Products page under the cards. Leave empty to hide." />
                    <p class="mb-0"><a href="{{ route('admin.products.index') }}">Manage products</a></p>
                </div>
            </x-admin.card>

            <x-admin.card id="industries" title="Industries" description="Heading of the industries section. The industry cards themselves are managed under Content › Industries.">
                <div class="form-grid">
                    <x-admin.form.input name="industries[eyebrow]" label="Small text above the heading" :value="$industries['eyebrow']" maxlength="80" />
                    <x-admin.form.input name="industries[heading]" label="Heading" :value="$industries['heading']" required maxlength="120" />
                </div>
                <x-slot:footer>
                    <x-admin.button :href="route('admin.industries.index')" variant="secondary" size="small" icon-end="arrow-right">Manage industries</x-admin.button>
                </x-slot:footer>
            </x-admin.card>

            <x-admin.card id="counters" title="Counters" description="The headline, and the counters that count up when visitors scroll to them.">
                <div class="form-stack">
                    <div class="form-grid">
                        <x-admin.form.input name="stats[word]" label="Headline" :value="$stats['word']" maxlength="100" counter hint="Up to 100 characters. Leave empty to hide." />
                        <x-admin.form.input name="stats[subtitle]" label="Line below the word" :value="$stats['subtitle']" maxlength="160" />
                    </div>
                    <x-admin.form.image-upload name="stats_word_image" label="Photo inside the headline (optional)" :current-url="$stats['word_image_url']" accept="image/jpeg,image/png,image/webp" remove-name="remove_stats_word_image" hint="Wide colourful photo, about 1600 × 500 px. Without it the headline is shown in the brand colour." />

                    <fieldset class="fieldset">
                        <legend class="form-label">Counters</legend>
                        <div class="form-grid">
                            @foreach ($statRows as $index => $row)
                                <x-admin.form.input name="stats[items][{{ $index }}][value]" label="Counter {{ $index + 1 }}: number" :value="$row['value'] ?? ''" maxlength="12" placeholder="e.g. 500+" />
                                <x-admin.form.input name="stats[items][{{ $index }}][label]" label="Counter {{ $index + 1 }}: label" :value="$row['label'] ?? ''" maxlength="60" placeholder="e.g. Projects delivered" />
                            @endforeach
                        </div>
                        <p class="form-text mb-0">Numbers can have text before or after them (500+, 98%, 1.2m). Empty counters are hidden.</p>
                    </fieldset>
                </div>
            </x-admin.card>

            <x-admin.card id="why-us" title="Why choose us" description="Cards with an icon and a short title.">
                <div class="form-stack">
                    <div class="form-grid">
                        <x-admin.form.input name="why_us[eyebrow]" label="Small text above the heading" :value="$whyUs['eyebrow']" maxlength="80" />
                        <x-admin.form.input name="why_us[heading]" label="Heading" :value="$whyUs['heading']" required maxlength="120" />
                    </div>

                    <fieldset class="fieldset">
                        <legend class="form-label">Cards</legend>
                        <div class="form-grid">
                            @foreach ($whyRows as $index => $row)
                                <x-admin.form.select name="why_us[items][{{ $index }}][icon]" label="Card {{ $index + 1 }}: icon" :options="$iconOptions" :value="$row['icon'] ?? ''" placeholder="Choose an icon" />
                                <x-admin.form.input name="why_us[items][{{ $index }}][title]" label="Card {{ $index + 1 }}: title" :value="$row['title'] ?? ''" maxlength="60" />
                            @endforeach
                        </div>
                        <p class="form-text mb-0">Cards without a title are hidden.</p>
                    </fieldset>
                </div>
            </x-admin.card>

            <x-admin.card id="certificates" title="Certificates" description="Shows the first {{ \App\Models\Certificate::HOME_LIMIT }} active certificates. Upload them in Certificates; the section is hidden while there are none.">
                <div class="form-stack">
                    <div class="form-grid">
                        <x-admin.form.input name="certificates[eyebrow]" label="Small text above the heading" :value="$certificates['eyebrow']" maxlength="80" />
                        <x-admin.form.input name="certificates[heading]" label="Heading" :value="$certificates['heading']" required maxlength="120" />
                    </div>
                    <x-admin.form.input name="certificates[link_label]" label="Link text" :value="$certificates['link_label']" maxlength="40" hint="Link to the Certificates page under the cards. Leave empty to hide." />
                    <p class="mb-0"><a href="{{ route('admin.certificates.index') }}">Manage certificates</a></p>
                </div>
            </x-admin.card>

            <div class="savebar">
                <x-admin.icon name="globe" class="savebar__icon" />
                <p class="savebar__text">Changes apply to the live home page as soon as you save.</p>
                <x-admin.button type="submit" icon="check">Save home page</x-admin.button>
            </div>
        </form>
    </div>
</x-admin.layout>
