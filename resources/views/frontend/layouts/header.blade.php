<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('frontend.home') }}" class="text-2xl font-extrabold text-brand-dark tracking-tight">
                    SEMI<span class="text-brand-orange">ECOMMERCE</span>
                </a>
            </div>

            <!-- Desktop Menu -->
            <nav class="hidden md:flex space-x-8">
                <a href="{{ route('frontend.home') }}" class="text-brand-dark font-semibold hover:text-brand-orange transition-colors">Home</a>
                <a href="#" class="text-gray-600 font-semibold hover:text-brand-orange transition-colors">About</a>
                <a href="#" class="text-gray-600 font-semibold hover:text-brand-orange transition-colors">Industries</a>
                <a href="#" class="text-gray-600 font-semibold hover:text-brand-orange transition-colors">Products</a>
                <a href="#" class="text-gray-600 font-semibold hover:text-brand-orange transition-colors">Blogs</a>
            </nav>

            <!-- CTA Button -->
            <div class="hidden md:flex items-center">
                <a href="#" class="bg-brand-orange text-white px-6 py-2.5 font-bold uppercase tracking-wide hover:bg-orange-600 transition-colors">
                    Get a Quote
                </a>
            </div>
            
            <!-- Mobile menu button (Functionality can be added in main.js later) -->
            <div class="md:hidden flex items-center">
                <button class="text-gray-600 hover:text-brand-dark focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</header>