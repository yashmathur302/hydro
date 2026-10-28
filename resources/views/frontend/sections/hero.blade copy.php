@php
    $bgImage = isset($section->image['primary']) ? asset($section->image['primary']) : 'https://via.placeholder.com/1920x1080';
@endphp
<section class="relative bg-brand-dark w-full min-h-[80vh] flex items-center bg-cover bg-center" style="background-image: url('{{ $bgImage }}');">
    <!-- Dark Overlay -->
    <div class="absolute inset-0 bg-brand-dark/80"></div>
    
    <div class="relative z-10 container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            @if($section->subtitle)
                <span class="inline-block py-1 px-3 bg-brand-orange text-white text-sm font-semibold tracking-wider uppercase mb-4">
                    {{ $section->subtitle }}
                </span>
            @endif
            
            <h1 class="text-4xl md:text-6xl font-extrabold text-white leading-tight mb-6">
                {{ $section->title ?? 'Default Hero Title' }}
            </h1>
            
            @if($section->content)
                <div class="text-lg text-gray-200 mb-8 max-w-2xl">
                    {!! $section->content !!}
                </div>
            @endif

            @if($section->button_text)
                <a href="{{ $section->button_link ?? '#' }}" class="inline-flex items-center justify-center px-8 py-4 bg-brand-orange text-white font-bold uppercase tracking-wider hover:bg-orange-600 transition-colors duration-300">
                    {{ $section->button_text }}
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            @endif
        </div>
    </div>
</section>