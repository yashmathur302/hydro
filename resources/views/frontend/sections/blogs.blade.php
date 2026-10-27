<section class="py-20 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            @if($section->subtitle)
                <h4 class="text-brand-orange font-bold uppercase tracking-wider mb-2">{{ $section->subtitle }}</h4>
            @endif
            <h2 class="text-3xl md:text-5xl font-extrabold text-brand-dark">{{ $section->title ?? 'Latest News & Articles' }}</h2>
        </div>

        <!-- Blogs Grid (Dynamic) -->
        @if(isset($section->dynamic_blogs) && $section->dynamic_blogs->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($section->dynamic_blogs as $post)
                    <div class="bg-white shadow-lg overflow-hidden group border border-gray-100 flex flex-col h-full">
                        <div class="relative overflow-hidden h-60">
                            @php
                                $blogImg = $post->featured_image ? asset($post->featured_image) : 'https://via.placeholder.com/600x400?text=News';
                            @endphp
                            <img src="{{ $blogImg }}" alt="{{ $post->title }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                            
                            <!-- Date Badge (Logistika theme style) -->
                            <div class="absolute top-4 left-4 bg-brand-orange text-white px-4 py-2 font-bold text-sm text-center uppercase leading-tight shadow-md">
                                <span class="block text-xl">{{ \Carbon\Carbon::parse($post->published_at)->format('d') }}</span>
                                <span class="block text-xs">{{ \Carbon\Carbon::parse($post->published_at)->format('M Y') }}</span>
                            </div>
                        </div>
                        
                        <div class="p-8 flex-grow flex flex-col">
                            <!-- Meta -->
                            <div class="text-sm text-gray-500 font-semibold mb-4 flex items-center space-x-4 uppercase tracking-wider">
                                <span class="flex items-center text-brand-orange">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    {{ $post->author->name ?? 'Admin' }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                    {{ $post->category->name ?? 'Uncategorized' }}
                                </span>
                            </div>
                            
                            <!-- Title -->
                            <h3 class="text-2xl font-bold text-brand-dark mb-4 leading-snug group-hover:text-brand-orange transition-colors">
                                <a href="#">{{ Str::limit($post->title, 60) }}</a>
                            </h3>
                            
                            <!-- Excerpt -->
                            <p class="text-gray-600 mb-6 flex-grow">
                                {{ Str::limit($post->excerpt ?? strip_tags($post->content), 100) }}
                            </p>
                            
                            <!-- Read More -->
                            <div class="mt-auto">
                                <a href="#" class="text-brand-dark font-bold uppercase text-sm flex items-center hover:text-brand-orange transition-colors tracking-widest">
                                    Read More 
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center text-gray-500 py-10">No blogs published yet.</div>
        @endif
        
    </div>
</section>