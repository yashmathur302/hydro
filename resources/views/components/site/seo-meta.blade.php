{{-- Search engine and social sharing tags for the current page. --}}
@props(['seo'])

<title>{{ $seo->title }}</title>
@if ($seo->description)
    <meta name="description" content="{{ $seo->description }}">
@endif
@if ($seo->keywords)
    <meta name="keywords" content="{{ $seo->keywords }}">
@endif
<meta name="robots" content="{{ $seo->indexable ? 'index, follow' : 'noindex, nofollow' }}">
<link rel="canonical" href="{{ $seo->canonicalUrl }}">

<meta property="og:type" content="{{ $seo->type }}">
<meta property="og:site_name" content="{{ $seo->siteName }}">
<meta property="og:title" content="{{ $seo->title }}">
<meta property="og:url" content="{{ $seo->canonicalUrl }}">
<meta property="og:locale" content="en_US">
@if ($seo->description)
    <meta property="og:description" content="{{ $seo->description }}">
@endif
@if ($seo->imageUrl)
    <meta property="og:image" content="{{ $seo->imageUrl }}">
@endif

<meta name="twitter:card" content="{{ $seo->imageUrl ? 'summary_large_image' : 'summary' }}">
<meta name="twitter:title" content="{{ $seo->title }}">
@if ($seo->description)
    <meta name="twitter:description" content="{{ $seo->description }}">
@endif
@if ($seo->imageUrl)
    <meta name="twitter:image" content="{{ $seo->imageUrl }}">
@endif
