<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Dynamic SEO Settings -->
    <title>{{ $page->meta_title ?? $page->title ?? 'Semi Ecommerce Product Catalogue' }}</title>
    <meta name="description" content="{{ $page->meta_description ?? 'Explore our wide range of products and services.' }}">
    
    <!-- Open Graph / Social Media -->
    <meta property="og:title" content="{{ $page->meta_title ?? $page->title ?? 'Semi Ecommerce' }}">
    <meta property="og:description" content="{{ $page->meta_description ?? '' }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ request()->url() }}">
    @if(isset($page->og_image))
        <meta property="og:image" content="{{ asset($page->og_image) }}">
    @endif

    <!-- Robots Directives -->
    <meta name="robots" content="index, follow">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ request()->url() }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Compiled Tailwind CSS & JS -->
    @vite(['resources/css/main.css', 'resources/js/main.js'])
</head>
<body class="bg-white text-gray-800 font-sans antialiased overflow-x-hidden">

    @include('frontend.layouts.header')

    <main class="min-h-screen">
        @yield('content')
    </main>

    @include('frontend.layouts.footer')

</body>
</html>