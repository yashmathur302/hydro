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
        <div class="dashboard__main">
            <x-admin.card title="Overview" flush>
                <dl class="stat-grid">
                    <div class="stat">
                        <dt class="stat__label">New enquiries</dt>
                        <dd class="stat__value">{{ $enquiryStats['new'] }}</dd>
                        <dd class="stat__hint">Waiting for a reply</dd>
                    </div>
                    <div class="stat">
                        <dt class="stat__label">Last 7 days</dt>
                        <dd class="stat__value">{{ $enquiryStats['lastSevenDays'] }}</dd>
                        <dd class="stat__hint">Enquiries and messages</dd>
                    </div>
                    <div class="stat">
                        <dt class="stat__label">Products</dt>
                        <dd class="stat__value">{{ $productStats['total'] }}</dd>
                        <dd class="stat__hint">In {{ $productStats['categories'] }} {{ Str::plural('category', $productStats['categories']) }}</dd>
                    </div>
                    <div class="stat">
                        <dt class="stat__label">All-time enquiries</dt>
                        <dd class="stat__value">{{ $enquiryStats['products'] + $enquiryStats['contacts'] }}</dd>
                        <dd class="stat__hint">{{ $enquiryStats['products'] }} product · {{ $enquiryStats['contacts'] }} contact</dd>
                    </div>
                </dl>
            </x-admin.card>

            <x-admin.card title="Recent enquiries" :flush="$recentEnquiries->isNotEmpty()">
                <x-slot:actions>
                    <a class="see-all" href="{{ route('admin.enquiries.index') }}">See all <x-admin.icon name="arrow-right" /></a>
                </x-slot:actions>

                @if ($recentEnquiries->isEmpty())
                    <x-admin.empty-state icon="inbox" title="No enquiries yet" text="When visitors send an enquiry or contact message, it appears here." />
                @else
                    <ul class="activity">
                        @foreach ($recentEnquiries as $enquiry)
                            <li>
                                <a class="activity__item" href="{{ route('admin.enquiries.show', $enquiry) }}">
                                    <span class="avatar avatar--small avatar--soft" aria-hidden="true">{{ mb_strtoupper(mb_substr($enquiry->name, 0, 1)) }}</span>
                                    <span class="activity__text">
                                        <span><strong>{{ $enquiry->name }}</strong> {{ $enquiry->product_name ? 'asked about '.$enquiry->product_name : 'sent a contact message' }}</span>
                                        <span class="activity__time">{{ $enquiry->created_at->diffForHumans() }}</span>
                                    </span>
                                    @if ($enquiry->isUnread())
                                        <x-admin.badge variant="info">New</x-admin.badge>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-admin.card>
        </div>

        <div class="dashboard__side">
            <x-admin.card title="Products">
                <x-slot:actions>
                    <a class="see-all" href="{{ route('admin.products.index') }}">See all <x-admin.icon name="arrow-right" /></a>
                </x-slot:actions>

                <ul class="row-list">
                    <li class="row-list__item"><span>Active</span><strong class="row-list__number">{{ $productStats['active'] }}</strong></li>
                    <li class="row-list__item"><span>Hidden</span><strong class="row-list__number">{{ $productStats['inactive'] }}</strong></li>
                    <li class="row-list__item"><span>Featured</span><strong class="row-list__number">{{ $productStats['featured'] }}</strong></li>
                </ul>
            </x-admin.card>

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

                <ul class="row-list row-list--padded">
                    @foreach ($checklist as $item)
                        <li class="row-list__item">
                            <span>{{ $item['label'] }}</span>
                            @if ($item['done'])
                                <x-admin.badge variant="success">Done</x-admin.badge>
                            @else
                                <x-admin.badge>Not set</x-admin.badge>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </x-admin.card>
        </div>
    </div>
</x-admin.layout>
