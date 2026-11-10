{{-- Social profile icons from Site settings; renders nothing when none are filled in. --}}
@inject('siteSettings', 'App\Services\SiteSettingService')

@php($socialLinks = $siteSettings->socialLinks())

@if ($socialLinks !== [])
    <ul {{ $attributes->merge(['class' => 'social-list']) }}>
        @foreach ($socialLinks as $icon => $link)
            <li>
                <a class="social-list__link" href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $link['label'] }}">
                    <x-site.icon :name="$icon" />
                </a>
            </li>
        @endforeach
    </ul>
@endif
