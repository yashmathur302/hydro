<section class="py-20 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row items-center gap-16">
            
            <!-- Images Left -->
            <div class="w-full lg:w-1/2 relative">
                @if(isset($section->image['primary']))
                    <img src="{{ asset($section->image['primary']) }}" alt="About Us" class="w-full h-auto object-cover rounded shadow-xl">
                @endif
                @if(isset($section->image['secondary']))
                    <img src="{{ asset($section->image['secondary']) }}" alt="About Detail" class="absolute -bottom-10 -right-10 w-2/3 border-8 border-white shadow-2xl hidden md:block">
                @endif
            </div>

            <!-- Content Right -->
            <div class="w-full lg:w-1/2 mt-12 lg:mt-0">
                @if($section->subtitle)
                    <h4 class="text-brand-orange font-bold uppercase tracking-wider mb-2">{{ $section->subtitle }}</h4>
                @endif
                
                <h2 class="text-3xl md:text-5xl font-extrabold text-brand-dark mb-6 leading-tight">
                    {{ $section->title }}
                </h2>
                
                <div class="text-gray-600 prose max-w-none mb-8">
                    {!! $section->content !!}
                </div>

                @if($section->button_text)
                    <a href="{{ $section->button_link ?? '#' }}" class="inline-block px-8 py-3 bg-brand-dark text-white font-semibold uppercase hover:bg-brand-orange transition-colors duration-300">
                        {{ $section->button_text }}
                    </a>
                @endif
            </div>
            
        </div>
    </div>
</section>