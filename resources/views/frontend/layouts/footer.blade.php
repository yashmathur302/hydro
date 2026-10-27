<footer class="bg-brand-dark text-gray-300 pt-16 pb-8 border-t-4 border-brand-orange">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
            
            <!-- About Widget -->
            <div>
                <a href="{{ route('frontend.home') }}" class="text-2xl font-extrabold text-white tracking-tight mb-6 block">
                    SEMI<span class="text-brand-orange">ECOMMERCE</span>
                </a>
                <p class="text-gray-400 mb-6 leading-relaxed">
                    We provide top-notch industrial products and solutions. Our commitment to quality makes us the industry leader.
                </p>
                <div class="flex space-x-4">
                    <!-- Social Icons (Placeholders) -->
                    <a href="#" class="w-10 h-10 bg-gray-800 flex items-center justify-center rounded hover:bg-brand-orange text-white transition-colors"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="w-10 h-10 bg-gray-800 flex items-center justify-center rounded hover:bg-brand-orange text-white transition-colors"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="w-10 h-10 bg-gray-800 flex items-center justify-center rounded hover:bg-brand-orange text-white transition-colors"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-lg font-bold text-white mb-6 uppercase">Quick Links</h4>
                <ul class="space-y-3">
                    <li><a href="#" class="hover:text-brand-orange transition-colors">About Us</a></li>
                    <li><a href="#" class="hover:text-brand-orange transition-colors">Our Products</a></li>
                    <li><a href="#" class="hover:text-brand-orange transition-colors">Certificates</a></li>
                    <li><a href="#" class="hover:text-brand-orange transition-colors">Latest News</a></li>
                    <li><a href="#" class="hover:text-brand-orange transition-colors">Contact Us</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h4 class="text-lg font-bold text-white mb-6 uppercase">Contact Us</h4>
                <ul class="space-y-4">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-brand-orange mt-1 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span>123 Industrial Area, New Delhi, India 110044</span>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-brand-orange mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        <span>+91 98765 43210</span>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-brand-orange mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <span>info@semiecommerce.com</span>
                    </li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div>
                <h4 class="text-lg font-bold text-white mb-6 uppercase">Newsletter</h4>
                <p class="text-gray-400 mb-4">Subscribe to our newsletter to get the latest updates.</p>
                <form action="#" class="flex flex-col space-y-3">
                    <input type="email" placeholder="Your Email Address" class="bg-gray-800 text-white px-4 py-3 focus:outline-none focus:ring-1 focus:ring-brand-orange border-none w-full">
                    <button type="submit" class="bg-brand-orange text-white px-4 py-3 font-bold uppercase hover:bg-orange-600 transition-colors w-full">Subscribe</button>
                </form>
            </div>
            
        </div>
        <div class="border-t border-gray-800 pt-8 text-center text-gray-500 text-sm">
            <p>&copy; {{ date('Y') }} Semi Ecommerce. All Rights Reserved. Designed perfectly.</p>
        </div>
    </div>
</footer>