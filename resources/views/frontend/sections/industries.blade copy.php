<section class="py-20 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto mb-16">
            @if($section->subtitle)
                <h4 class="text-brand-orange font-bold uppercase tracking-wider mb-2">{{ $section->subtitle }}</h4>
            @endif
            <h2 class="text-3xl md:text-5xl font-extrabold text-brand-dark mb-4">{{ $section->title ?? 'Industries We Serve' }}</h2>
            @if($section->content)
                <div class="text-gray-600 prose mx-auto">
                    {!! $section->content !!}
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Simulated Grid Items -->
            @php
                $industries = ['Construction', 'Manufacturing', 'Automotive', 'Logistics'];
            @endphp
            @foreach($industries as $ind)
            <div class="bg-brand-light p-8 text-center group hover:bg-brand-dark transition-colors duration-300">
                <div class="w-16 h-16 bg-brand-orange text-white flex items-center justify-center rounded-full mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-brand-dark mb-3 group-hover:text-white">{{ $ind }}</h3>
                <p class="text-gray-500 group-hover:text-gray-300 text-sm">Providing robust solutions for the {{ strtolower($ind) }} sector.</p>
            </div>
            @endforeach
        </div>
        
    </div>
</section>