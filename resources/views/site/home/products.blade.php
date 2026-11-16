{{-- Products: featured products first, then the rest; hidden when there are none. --}}
@if ($products->isNotEmpty())
    <section class="home-products section home-band" aria-labelledby="home-products-heading">
        <div class="container">
            <header class="section-head section-head--center">
                @if ($productsSection['eyebrow'])
                    <p class="eyebrow"><x-site.icon name="bolt" />{{ $productsSection['eyebrow'] }}</p>
                @endif
                <h2 class="section-title" id="home-products-heading">{{ $productsSection['heading'] }}</h2>
            </header>

            <ul class="product-grid">
                @foreach ($products as $product)
                    <li><x-site.product-card :product="$product" /></li>
                @endforeach
            </ul>

            @if ($productsSection['link_label'])
                <p class="home-products__more">
                    <a class="btn btn--accent" href="{{ route('products.index') }}">{{ $productsSection['link_label'] }}</a>
                </p>
            @endif
        </div>
    </section>
@endif
