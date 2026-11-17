{{--
    About Us page: banner (AboutSection), then the home page sections with the
    same content. The About block's "More About Us" button is hidden here.
--}}
<x-site.layout :seo="$seo">
    <x-site.page-banner :title="$banner['title']" :eyebrow="$banner['eyebrow']" :image-url="$banner['background_image_url']" />

    @include('site.home.about', ['showButton' => false])
    @include('site.home.why-us')
    @include('site.home.clients')
    @include('site.home.stats')
</x-site.layout>
