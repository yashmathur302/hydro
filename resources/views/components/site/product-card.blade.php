{{-- One product tile: photo (cut-out shots get a plain frame) and name. --}}
@props(['product', 'lazy' => true])

@php($image = $product->primaryImage)

<article {{ $attributes->class('product-card') }}>
    <div @class(['product-card__frame', 'product-card__frame--cutout' => $image?->hasPlainBackground()])>
        @if ($image)
            <img class="product-card__image" src="{{ $image->thumbnailUrl() }}" alt="{{ $image->alt_text ?: $product->name }}" @if ($lazy) loading="lazy" @endif decoding="async">
        @else
            <x-site.icon name="image" class="product-card__placeholder" />
        @endif
    </div>
    <h3 class="product-card__name">{{ $product->name }}</h3>
</article>
