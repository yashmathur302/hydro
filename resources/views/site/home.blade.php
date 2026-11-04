{{-- Temporary holding page. Replaced when the Home page design is shared. --}}
@inject('siteSettings', 'App\Services\SiteSettingService')

<x-site.layout :seo="$seo">
    <section class="holding">
        <h1 class="holding__title">{{ $siteSettings->get(\App\Enums\SiteSettingKey::SiteName, config('app.name')) }}</h1>
        <p class="holding__text">Our new website is on its way.</p>
    </section>
</x-site.layout>
