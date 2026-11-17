{{-- Friendly error page. Never shows exception details, paths or queries. --}}
@props(['code', 'title', 'message'])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>{{ $title }}</title>
    @vite('resources/css/error.css')
</head>
<body>
    <main class="error-page">
        <p class="error-page__code">{{ $code }}</p>
        <h1 class="error-page__title">{{ $title }}</h1>
        <p class="error-page__message">{{ $message }}</p>
        <a class="error-page__link" href="{{ url('/') }}">Go to the home page</a>
    </main>
</body>
</html>
