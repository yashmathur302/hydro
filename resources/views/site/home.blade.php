{{-- Home page. Sections are added one by one as each part of the design is approved. --}}
<x-site.layout :seo="$seo">
    @include('site.home.hero')
    @include('site.home.about')
</x-site.layout>
