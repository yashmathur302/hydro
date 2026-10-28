@php
    // DB ke JSON string ko safely array me convert kar rahe hain
    $secSettings = is_string($section->settings) ? json_decode($section->settings, true) : ($section->settings ?? []);
@endphp

<section class="py-16" style="background-color: #f4f6f9;">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center bg-white p-10 md:p-16 shadow-lg border-t-4" style="border-color: #ff5e14;">
            
            @if($section->subtitle)
                <h4 class="font-bold uppercase tracking-widest mb-4" style="color: #ff5e14; font-size: 14px;">
                    {{ $section->subtitle }}
                </h4>
            @endif
            
            <h2 class="text-3xl md:text-5xl font-extrabold mb-6" style="color: #012836;">
                {{ $section->title ?? 'Download Our Company Certificate' }}
            </h2>
            
            @if($section->content)
                <div class="prose max-w-none text-gray-600 mb-10 mx-auto" style="font-size: 1.1rem; line-height: 1.7;">
                    {!! $section->content !!}
                </div>
            @endif

            <!-- Ab hum $secSettings variable use kar rahe hain -->
            @if(isset($secSettings['certificate_file']))
                <a href="{{ asset($secSettings['certificate_file']) }}" download class="inline-flex items-center px-10 py-4 text-white font-bold uppercase tracking-wider transition-colors duration-300 shadow-md hover:shadow-xl" style="background-color: #ff5e14;">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Download Certificate
                </a>
            @else
                <p class="text-gray-400 italic">Certificate file is not uploaded yet.</p>
            @endif
            
        </div>
    </div>
</section>