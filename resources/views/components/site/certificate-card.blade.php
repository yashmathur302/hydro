{{--
    Certificate card: cover image (or a certificate icon), name and issuer.
    The whole card opens the PDF in a new browser tab.
--}}
@props(['certificate', 'headingLevel' => 3])

@php($coverUrl = $certificate->coverUrl())

<article class="certificate-card">
    <a class="certificate-card__link" href="{{ $certificate->fileUrl() }}" target="_blank" rel="noopener">
        <span @class(['certificate-card__frame', 'certificate-card__frame--placeholder' => ! $coverUrl])>
            @if ($coverUrl)
                <img class="certificate-card__image" src="{{ $coverUrl }}" alt="" loading="lazy" decoding="async">
            @else
                <x-site.feature-icon name="certificate" class="certificate-card__icon" />
                <span class="certificate-card__type">PDF</span>
            @endif
        </span>
        <span class="certificate-card__body">
            <h{{ $headingLevel }} class="certificate-card__name">{{ $certificate->name }}</h{{ $headingLevel }}>
            @if ($certificate->issuer)
                <span class="certificate-card__issuer">{{ $certificate->issuer }}</span>
            @endif
            <span class="certificate-card__action">
                View Certificate <x-site.icon name="external" />
                <span class="visually-hidden">(PDF, opens in a new tab)</span>
            </span>
        </span>
    </a>
</article>
