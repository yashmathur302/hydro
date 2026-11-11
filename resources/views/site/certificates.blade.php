{{-- Certificates page: banner and every active certificate (PDFs open in a new tab). --}}
<x-site.layout :seo="$seo">
    <x-site.page-banner :title="$banner['title']" :eyebrow="$banner['eyebrow']" :image-url="$banner['background_image_url']" />

    <section class="certificates section" aria-label="All certificates">
        <div class="container">
            @if ($certificates->isEmpty())
                <p class="certificates__empty">Certificates will be published here soon.</p>
            @else
                <ul class="certificate-grid">
                    @foreach ($certificates as $certificate)
                        <li><x-site.certificate-card :certificate="$certificate" :heading-level="2" /></li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>
</x-site.layout>
