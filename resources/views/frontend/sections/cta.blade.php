@php
    $bgStyle = isset($section->settings['cta_bg_color']) ? "background-color: ".$section->settings['cta_bg_color'].";" : "background-color: #ff5e14;";
    $layoutClass = (isset($section->settings['cta_layout']) && $section->settings['cta_layout'] == 'left') ? "text-left flex-col md:flex-row md:justify-between" : "text-center flex-col justify-center";
@endphp
<section class="py-16 text-white bg-brand-orange" style="{{ $bgStyle }}">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center {{ $layoutClass }}">
            
            <div class="max-w-3xl {{ $layoutClass == 'text-center flex-col justify-center' ? 'mx-auto' : '' }}">
                <h2 class="text-3xl md:text-5xl font-extrabold mb-4">{{ $section->title ?? 'Have A Project In Mind?' }}</h2>
                @if($section->content)
                    <div class="text-lg opacity-90 mb-8 prose prose-invert max-w-none">
                        {!! $section->content !!}
                    </div>
                @endif
            </div>

            @if($section->button_text)
                <div class="{{ $layoutClass == 'text-center flex-col justify-center' ? '' : 'mt-8 md:mt-0 flex-shrink-0' }}">
                    <a href="{{ $section->button_link ?? '#' }}" class="inline-block px-10 py-4 bg-brand-dark text-white font-bold uppercase tracking-wider hover:bg-white hover:text-brand-dark transition-colors duration-300 shadow-xl">
                        {{ $section->button_text }}
                    </a>
                </div>
            @endif
            
        </div>
    </div>
</section>