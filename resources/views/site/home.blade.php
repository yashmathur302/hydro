{{-- Home page. Sections are added one by one as each part of the design is approved. --}}
<x-site.layout :seo="$seo">
    @include('site.home.hero')
    @include('site.home.about')
    @include('site.home.industries')
    @include('site.home.stats')
    @include('site.home.why-us')
    @include('site.home.clients')
</x-site.layout>
