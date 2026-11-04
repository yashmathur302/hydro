<x-admin.layout title="Dashboard">
    <x-admin.page-header title="Hello, {{ auth()->user()->name }}" description="Here is where your website setup stands." />

    <div class="grid grid--dashboard">
        <x-admin.card title="Setup checklist" description="Complete these settings before the website goes live.">
            <div class="progress-summary">
                <p class="progress-summary__value">
                    <strong>{{ $completedCount }}</strong> of {{ $checklist->count() }} done
                </p>
                <progress class="progress" max="{{ $checklist->count() }}" value="{{ $completedCount }}" aria-label="Setup progress">
                    {{ $completedCount }} of {{ $checklist->count() }}
                </progress>
            </div>

            <ul class="checklist">
                @foreach ($checklist as $item)
                    <li @class(['checklist__item', 'is-done' => $item['done']])>
                        <span class="checklist__status">
                            <x-admin.icon :name="$item['done'] ? 'check' : 'circle'" />
                        </span>
                        <span class="checklist__label">{{ $item['label'] }}</span>
                        <span class="visually-hidden">{{ $item['done'] ? 'done' : 'not set' }}</span>
                    </li>
                @endforeach
            </ul>

            <x-slot:footer>
                <x-admin.button :href="route('admin.settings.edit')" variant="secondary" icon="settings">Open site settings</x-admin.button>
            </x-slot:footer>
        </x-admin.card>

        <x-admin.card title="Your account">
            <dl class="details">
                <div class="details__row">
                    <dt>Email</dt>
                    <dd>{{ auth()->user()->email }}</dd>
                </div>
                <div class="details__row">
                    <dt>Last login</dt>
                    <dd>{{ auth()->user()->last_login_at?->format('d M Y, h:i A') ?? 'First login' }}</dd>
                </div>
            </dl>

            <x-slot:footer>
                <x-admin.button :href="route('admin.account.edit')" variant="secondary" icon="user">Manage account</x-admin.button>
            </x-slot:footer>
        </x-admin.card>
    </div>
</x-admin.layout>
