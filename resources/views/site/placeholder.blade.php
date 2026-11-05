{{-- Temporary page for menu items whose design is not built yet. --}}
<x-site.layout :seo="$seo">
    <section class="section placeholder-page">
        <div class="container">
            <h1 class="section-title">{{ $title }}</h1>
            <p class="placeholder-page__text">This page is being prepared and will be available soon.</p>
            <a class="btn btn--accent" href="{{ route('home') }}">Back to home</a>
        </div>
    </section>
</x-site.layout>
