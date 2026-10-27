<section class="py-20 bg-brand-light">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            @if($section->subtitle)
                <h4 class="text-brand-orange font-bold uppercase tracking-wider mb-2">{{ $section->subtitle }}</h4>
            @endif
            <h2 class="text-3xl md:text-5xl font-extrabold text-brand-dark">{{ $section->title ?? 'Our Products' }}</h2>
        </div>

        <!-- Products Grid (Dynamic) -->
        @if(isset($section->dynamic_products) && $section->dynamic_products->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach($section->dynamic_products as $product)
                    <div class="group relative overflow-hidden bg-white shadow-lg border border-gray-100 flex flex-col h-full">
                        <!-- Thumbnail Image -->
                        <div class="relative h-64 w-full overflow-hidden">
                            @php
                                $imgSrc = $product->thumbnail ? asset($product->thumbnail) : 'https://via.placeholder.com/600x400?text=No+Image';
                            @endphp
                            <img src="{{ $imgSrc }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            
                            <!-- Hover Overlay with CTA -->
                            <div class="absolute inset-0 bg-brand-dark/80 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                <!-- Link is # for now, will be updated when inner pages are built -->
                                <a href="#" class="px-6 py-2 bg-brand-orange text-white font-bold uppercase tracking-wide transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                                    View Details
                                </a>
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="p-6 text-center border-t border-gray-100 flex-grow flex flex-col justify-center">
                            <h3 class="text-xl font-bold text-brand-dark mb-2 leading-tight">{{ $product->name }}</h3>
                            <p class="text-brand-orange text-sm font-semibold uppercase tracking-wider">
                                {{ $product->category->name ?? 'Uncategorized' }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center text-gray-500 py-10">No products found for this section.</div>
        @endif

        <!-- View All Button -->
        @if($section->button_text)
            <div class="text-center mt-12">
                <a href="{{ $section->button_link ?? '#' }}" class="inline-block px-10 py-4 bg-brand-dark text-white font-bold uppercase tracking-wider hover:bg-brand-orange transition-colors duration-300 shadow-md">
                    {{ $section->button_text }}
                </a>
            </div>
        @endif
        
    </div>
</section>