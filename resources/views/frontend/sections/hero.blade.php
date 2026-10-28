@php
    $bannerImages = isset($section->image['banners']) && is_array($section->image['banners']) && count($section->image['banners']) > 0 
                    ? $section->image['banners'] 
                    : ['https://via.placeholder.com/1920x1080']; 
@endphp

<!-- Swiper Library -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Custom CSS ONLY for this Hero Section -->
<style>
    .custom-hero-section {
        position: relative;
        width: 100%;
        min-height: 85vh; /* Fix height explicitly */
        background-color: #012836;
        display: flex;
        align-items: center;
        overflow: hidden;
    }
    .custom-hero-slider {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 0;
    }
    .custom-hero-slide {
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
    .custom-hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        /* Exact Logistika Gradient */
        background: linear-gradient(90deg, #012836 10%, rgba(1,40,54,0.85) 40%, rgba(1,40,54,0) 100%);
        z-index: 1;
        pointer-events: none;
    }
    .custom-hero-content-wrapper {
        position: relative;
        z-index: 2;
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }
    .custom-hero-text-box {
        max-width: 650px;
        text-align: left;
    }
    .custom-hero-subtitle {
        color: #ff5e14;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-size: 14px;
        margin-bottom: 15px;
        display: block;
    }
    .custom-hero-title {
        color: #ffffff;
        font-size: 65px;
        font-weight: 900;
        line-height: 1.1;
        margin-bottom: 25px;
        margin-top: 0;
    }
    .custom-hero-desc {
        color: #d1d5db;
        font-size: 18px;
        margin-bottom: 40px;
        line-height: 1.6;
    }
    .custom-hero-action-row {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 30px;
    }
    .custom-hero-btn {
        background-color: #ff5e14;
        color: #ffffff;
        padding: 16px 35px;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 1px;
        text-decoration: none;
        transition: 0.3s ease;
        display: inline-block;
    }
    .custom-hero-btn:hover {
        background-color: #ffffff;
        color: #ff5e14;
    }
    .custom-hero-call-block {
        display: flex;
        align-items: center;
        gap: 15px;
        color: #ffffff;
    }
    .custom-hero-icon-box {
        width: 50px;
        height: 50px;
        background-color: #033b4f;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ff5e14;
    }
    .custom-hero-call-text p {
        margin: 0;
    }
    .call-label {
        font-size: 14px;
        color: #9ca3af;
    }
    .call-number {
        font-size: 18px;
        font-weight: 700;
    }
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .custom-hero-title { font-size: 40px; }
        .custom-hero-overlay { background: linear-gradient(90deg, #012836 10%, rgba(1,40,54,0.95) 70%, rgba(1,40,54,0.8) 100%); }
    }
    
    /* Swiper Pagination Overrides */
    .custom-hero-section .swiper-pagination {
        text-align: left;
        padding-left: 20px;
        max-width: 1200px;
        margin: 0 auto;
        left: 0;
        right: 0;
        bottom: 30px !important;
    }
    .custom-hero-section .swiper-pagination-bullet {
        background: #ffffff !important;
        opacity: 0.5 !important;
        width: 12px !important;
        height: 12px !important;
        margin-right: 8px !important;
    }
    .custom-hero-section .swiper-pagination-bullet-active {
        background: #ff5e14 !important;
        opacity: 1 !important;
    }
</style>

<!-- HTML Structure using Custom CSS -->
<section class="custom-hero-section">
    
    <!-- Swiper Background Container -->
    <div class="swiper hero-swiper custom-hero-slider">
        <div class="swiper-wrapper">
            @foreach($bannerImages as $img)
                <div class="swiper-slide custom-hero-slide" style="background-image: url('{{ asset($img) }}');"></div>
            @endforeach
        </div>
    </div>

    <!-- Gradient Overlay -->
    <div class="custom-hero-overlay"></div>

    <!-- Main Content -->
    <div class="custom-hero-content-wrapper">
        <div class="custom-hero-text-box">
            
            @if($section->subtitle)
                <span class="custom-hero-subtitle">{{ $section->subtitle }}</span>
            @endif
            
            <h1 class="custom-hero-title">{{ $section->title ?? 'Flexible Logistic & Cargo Service' }}</h1>
            
            @if($section->content)
                <div class="custom-hero-desc">
                    {!! $section->content !!}
                </div>
            @endif

            <div class="custom-hero-action-row">
                @if($section->button_text)
                    <a href="{{ $section->button_link ?? '#' }}" class="custom-hero-btn">
                        {{ $section->button_text }}
                    </a>
                @endif
                
                <div class="custom-hero-call-block">
                    <div class="custom-hero-icon-box">
                        <svg style="width:24px; height:24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    </div>
                    <div class="custom-hero-call-text">
                        <p class="call-label">Call Us Now</p>
                        <p class="call-number">+025 757 576 560</p>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    @if(count($bannerImages) > 1)
        <div class="swiper-pagination"></div>
    @endif
</section>

<!-- Initialize Swiper -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const swiper = new Swiper('.hero-swiper', {
            loop: true,
            effect: 'fade', 
            fadeEffect: { crossFade: true },
            autoplay: { delay: 5000, disableOnInteraction: false },
            pagination: { el: '.swiper-pagination', clickable: true },
        });
    });
</script>