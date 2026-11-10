{{-- Static page (Admin > Static Pages): banner with the title, then the Markdown content. --}}
<x-site.layout :seo="$seo">
    <x-site.page-banner :title="$page->title" />

    <section class="section static-page">
        <div class="container">
            @if ($content->toHtml() !== '')
                <div class="prose">{{ $content }}</div>
            @else
                <p class="static-page__empty">This page is being updated. Please check back soon.</p>
            @endif
        </div>
    </section>
</x-site.layout>
