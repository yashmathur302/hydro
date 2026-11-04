@php($remaining = $checklist->count() - $completedCount)

<x-admin.layout title="Dashboard">
    <x-admin.page-header title="Hello, {{ Str::before(auth()->user()->name, ' ') }}!">
        <x-admin.button :href="route('home')" icon="globe" target="_blank" rel="noopener">Open site</x-admin.button>
    </x-admin.page-header>

    @if ($remaining > 0)
        <div class="banner">
            <x-admin.icon name="sparkle" class="banner__icon" />
            <p class="banner__text">{{ $remaining }} {{ Str::plural('setting', $remaining) }} left before your website is ready to go live.</p>
            <x-admin.button :href="route('admin.settings.edit')" variant="secondary" size="small" icon-end="arrow-up-right">Complete setup</x-admin.button>
        </div>
    @endif

    <div class="dashboard">
        <x-admin.card title="Setup checklist" flush>
            <x-slot:actions>
                <a class="see-all" href="{{ route('admin.settings.edit') }}">Settings <x-admin.icon name="arrow-right" /></a>
            </x-slot:actions>

            <div class="progress-summary">
                <p class="progress-summary__label">Completed</p>
                <p class="progress-summary__value">{{ $completedCount }}<span>/{{ $checklist->count() }}</span></p>
                <progress class="progress" max="{{ $checklist->count() }}" value="{{ $completedCount }}" aria-label="Setup progress">
                    {{ $completedCount }} of {{ $checklist->count() }}
                </progress>
            </div>

            <ul class="row-list row-list--columns">
                @foreach ($checklist as $item)
                    <li class="row-list__item">
                        <span>{{ $item['label'] }}</span>
                        @if ($item['done'])
                            <span class="badge badge--success">Done</span>
                        @else
                            <span class="badge">Not set</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </x-admin.card>

        <x-admin.card title="Your account">
            <x-slot:actions>
                <a class="see-all" href="{{ route('admin.account.edit') }}">Manage <x-admin.icon name="arrow-right" /></a>
            </x-slot:actions>

            <dl class="row-list">
                <div class="row-list__item">
                    <dt>Email</dt>
                    <dd>{{ auth()->user()->email }}</dd>
                </div>
                <div class="row-list__item">
                    <dt>Last login</dt>
                    <dd>{{ auth()->user()->last_login_at?->format('d M Y, h:i A') ?? 'First login' }}</dd>
                </div>
            </dl>
        </x-admin.card>
    </div>
</x-admin.layout>
