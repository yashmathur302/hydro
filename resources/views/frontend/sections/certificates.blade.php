<section class="py-16 bg-brand-light border-y border-gray-200">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-12">
            @if($section->subtitle)
                <h4 class="text-brand-orange font-bold uppercase tracking-wider mb-2">{{ $section->subtitle }}</h4>
            @endif
            <h2 class="text-3xl md:text-4xl font-extrabold text-brand-dark">{{ $section->title ?? 'Our Certificates & Clients' }}</h2>
        </div>

        @if(isset($section->dynamic_clients) && $section->dynamic_clients->count() > 0)
            <div class="flex flex-wrap justify-center items-center gap-8 md:gap-16">
                @foreach($section->dynamic_clients as $client)
                    @if($client->logo)
                        <div class="w-32 h-20 md:w-40 md:h-24 flex items-center justify-center p-2 opacity-60 hover:opacity-100 grayscale hover:grayscale-0 transition-all duration-300">
                            <img src="{{ asset($client->logo) }}" alt="{{ $client->name }}" class="max-w-full max-h-full object-contain">
                        </div>
                    @else
                        <!-- Text fallback if no logo -->
                        <div class="w-32 h-20 md:w-40 md:h-24 bg-white shadow-sm flex items-center justify-center text-brand-dark font-bold text-center p-2 uppercase text-sm border border-gray-200 opacity-60 hover:opacity-100 transition-all duration-300">
                            {{ $client->name }}
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <div class="text-center text-gray-500">No clients or certificates added yet.</div>
        @endif
        
    </div>
</section>