{{-- Products page: banner, centred heading and introduction, product cards. --}}
<x-site.layout :seo="$seo">
    <x-site.page-banner :title="$banner['title']" :eyebrow="$banner['eyebrow']" :image-url="$banner['background_image_url']" />

    <section class="product-lines section" aria-labelledby="product-lines-heading">
        <div class="container">
            <header class="section-head section-head--center">
                <h2 class="section-title section-title--slashes" id="product-lines-heading">{{ $intro['heading'] }}</h2>
                @if ($intro['text'])
                    <p class="product-lines__intro">{{ $intro['text'] }}</p>
                @endif
            </header>

            @if ($products->isEmpty())
                <p class="product-lines__empty">Our product catalogue will be published here soon.</p>
            @else
                <ul class="product-grid">
                    @foreach ($products as $product)
                        <li><x-site.product-card :product="$product" :lazy="$loop->index >= 3" /></li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>
</x-site.layout>
