<section class="py-20 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto mb-16">
            @if($section->subtitle)
                <h4 class="text-brand-orange font-bold uppercase tracking-wider mb-2">{{ $section->subtitle }}</h4>
            @endif
            <h2 class="text-3xl md:text-5xl font-extrabold text-brand-dark">{{ $section->title ?? 'Latest News & Articles' }}</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Simulated Blog Loop -->
            @for($i=1; $i<=3; $i++)
            <div class="bg-white shadow-lg overflow-hidden group">
                <div class="relative overflow-hidden">
                    <img src="https://via.placeholder.com/600x400" alt="Blog Image" class="w-full h-56 object-cover transform group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute top-4 left-4 bg-brand-orange text-white px-3 py-1 font-bold text-sm">
                        {{ date('d M Y') }}
                    </div>
                </div>
                <div class="p-6">
                    <div class="text-sm text-gray-500 font-medium mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-brand-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        By Admin
                    </div>
                    <h3 class="text-xl font-bold text-brand-dark mb-4 leading-tight group-hover:text-brand-orange transition-colors">
                        <a href="#">Industry updates and technological advancements in our sector</a>
                    </h3>
                    <a href="#" class="text-brand-orange font-bold uppercase text-sm flex items-center hover:text-brand-dark transition-colors">
                        Read More 
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </div>
            @endfor
        </div>
        
    </div>
</section>