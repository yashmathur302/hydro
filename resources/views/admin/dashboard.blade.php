{{--
    Admin dashboard: live figures (cards), enquiry charts, recent enquiries and
    the setup checklist. Figures come from App\Services\DashboardStatistics; the
    page refreshes them every minute from admin.dashboard.statistics
    (see resources/js/admin/dashboard-charts.js). Every chart also has a
    visually hidden data table for screen readers.
--}}
@php
    $totals = $statistics['totals'];
    $remaining = $checklist->count() - $completedCount;
    $statCards = [
        ['key' => 'newEnquiries', 'label' => 'New enquiries', 'hint' => 'Waiting for a reply', 'icon' => 'inbox', 'tone' => 'primary'],
        ['key' => 'lastSevenDays', 'label' => 'Last 7 days', 'hint' => 'Enquiries and messages', 'icon' => 'mail', 'tone' => 'info'],
        ['key' => 'products', 'label' => 'Products', 'hint' => $totals['categories'].' '.Str::plural('category', $totals['categories']), 'icon' => 'box', 'tone' => 'success'],
        ['key' => 'allEnquiries', 'label' => 'All-time enquiries', 'hint' => $totals['productEnquiries'].' product · '.$totals['contactMessages'].' contact', 'icon' => 'star', 'tone' => 'warning'],
    ];
@endphp

<x-admin.layout title="Dashboard">
    <x-slot:header>
        <x-admin.page-header title="Hello, {{ Str::before(auth()->user()->name, ' ') }}!" description="Here is what is happening on your website.">
            <x-admin.button :href="route('home')" icon="globe" target="_blank" rel="noopener">Open site</x-admin.button>
        </x-admin.page-header>
    </x-slot:header>

    <div data-dashboard data-statistics-url="{{ route('admin.dashboard.statistics') }}">
        <script type="application/json" data-dashboard-data>@json($statistics)</script>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-4 g-3 mb-4">
            @foreach ($statCards as $card)
                <div class="col">
                    <div class="card card-slide h-100 mb-0">
                        <div class="card-body">
                            <div class="progress-widget">
                                <span class="stat-icon bg-soft-{{ $card['tone'] }} text-{{ $card['tone'] }}"><x-admin.icon :name="$card['icon']" class="icon-24" /></span>
                                <div class="progress-detail">
                                    <p class="mb-2">{{ $card['label'] }}</p>
                                    <h4 class="counter mb-1" data-stat="{{ $card['key'] }}">{{ $totals[$card['key']] }}</h4>
                                    <small class="text-secondary">{{ $card['hint'] }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($remaining > 0)
            <div class="alert alert-primary d-flex flex-wrap align-items-center gap-2">
                <x-admin.icon name="sparkle" class="icon-20 flex-shrink-0" />
                <p class="mb-0 flex-grow-1">{{ $remaining }} {{ Str::plural('setting', $remaining) }} left before your website is ready to go live.</p>
                <x-admin.button :href="route('admin.settings.edit')" variant="secondary" size="small" icon-end="arrow-right">Complete setup</x-admin.button>
            </div>
        @endif

        <div class="row g-3">
            <div class="col-lg-8">
                <x-admin.card title="Enquiries in the last 30 days" description="Product enquiries and contact messages per day.">
                    <x-slot:actions>
                        <span class="d-flex gap-3 small chart-legend">
                            <span><span class="chart-legend__dot chart-legend__dot--product"></span>Product</span>
                            <span><span class="chart-legend__dot chart-legend__dot--contact"></span>Contact</span>
                        </span>
                    </x-slot:actions>
                    <figure class="chart mb-0" data-chart="trend" aria-labelledby="trend-caption">
                        <figcaption class="visually-hidden" id="trend-caption">Enquiries per day, last 30 days</figcaption>
                        <table class="visually-hidden" data-chart-table>
                            <thead><tr><th scope="col">Day</th><th scope="col">Product enquiries</th><th scope="col">Contact messages</th></tr></thead>
                            <tbody>
                                @foreach ($statistics['trend'] as $day)
                                    <tr><th scope="row">{{ $day['label'] }}</th><td>{{ $day['product'] }}</td><td>{{ $day['contact'] }}</td></tr>
                                @endforeach
                            </tbody>
                        </table>
                    </figure>
                </x-admin.card>
            </div>

            <div class="col-lg-4">
                <x-admin.card title="Enquiry status">
                    <figure class="chart chart--donut mb-0" data-chart="statuses" aria-labelledby="status-caption">
                        <figcaption class="visually-hidden" id="status-caption">Enquiries by status</figcaption>
                    </figure>
                    <ul class="list-unstyled mb-0 mt-3 status-legend" data-status-legend>
                        @foreach ($statistics['statuses'] as $status)
                            <li class="d-flex justify-content-between align-items-center py-1">
                                <span><span class="chart-legend__dot chart-legend__dot--{{ $status['status'] }}"></span>{{ $status['label'] }}</span>
                                <strong data-status-count="{{ $status['status'] }}">{{ $status['count'] }}</strong>
                            </li>
                        @endforeach
                    </ul>
                </x-admin.card>
            </div>

            <div class="col-lg-8">
                <x-admin.card title="Recent enquiries" :flush="$recentEnquiries->isNotEmpty()">
                    <x-slot:actions>
                        <a class="btn btn-sm btn-soft-primary" href="{{ route('admin.enquiries.index') }}">See all</a>
                    </x-slot:actions>

                    @if ($recentEnquiries->isEmpty())
                        <x-admin.empty-state icon="inbox" title="No enquiries yet" text="When visitors send an enquiry or contact message, it appears here." />
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach ($recentEnquiries as $enquiry)
                                <li class="list-group-item">
                                    <a class="d-flex align-items-center gap-3 text-reset" href="{{ route('admin.enquiries.show', $enquiry) }}">
                                        <span class="avatar avatar-40 avatar-rounded avatar-initial avatar-initial--soft" aria-hidden="true">{{ mb_strtoupper(mb_substr($enquiry->name, 0, 1)) }}</span>
                                        <span class="flex-grow-1 min-w-0">
                                            <span class="d-block text-truncate"><strong>{{ $enquiry->name }}</strong> {{ $enquiry->product_name ? 'asked about '.$enquiry->product_name : 'sent a contact message' }}</span>
                                            <small class="text-secondary">{{ $enquiry->created_at->diffForHumans() }}</small>
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

            <div class="col-lg-4">
                <x-admin.card title="Most enquired products" description="Last {{ \App\Services\DashboardStatistics::TOP_PRODUCTS_DAYS }} days.">
                    <figure class="chart chart--bars mb-0" data-chart="topProducts" aria-labelledby="top-caption">
                        <figcaption class="visually-hidden" id="top-caption">Products with the most enquiries</figcaption>
                    </figure>
                    <p class="text-secondary small mb-0" data-top-empty @if ($statistics['topProducts'] !== []) hidden @endif>No product enquiries in this period yet.</p>
                </x-admin.card>
            </div>
        </div>

        <div class="row g-3 mt-0">
            <div class="col-lg-8">
                <x-admin.card title="Setup checklist" :description="$completedCount.' of '.$checklist->count().' done'">
                    <x-slot:actions>
                        <a class="btn btn-sm btn-soft-primary" href="{{ route('admin.settings.edit') }}">Settings</a>
                    </x-slot:actions>
                    <progress class="setup-progress mb-3" max="{{ $checklist->count() }}" value="{{ $completedCount }}" aria-label="Setup progress">{{ $completedCount }} of {{ $checklist->count() }}</progress>
                    <ul class="row row-cols-1 row-cols-md-2 g-2 list-unstyled mb-0">
                        @foreach ($checklist as $item)
                            <li class="col">
                                <div class="d-flex justify-content-between align-items-center border rounded px-3 py-2">
                                    <span>{{ $item['label'] }}</span>
                                    @if ($item['done'])
                                        <x-admin.badge variant="success">Done</x-admin.badge>
                                    @else
                                        <x-admin.badge>Not set</x-admin.badge>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </x-admin.card>
            </div>

            <div class="col-lg-4">
                <x-admin.card title="Products" flush>
                    <x-slot:actions>
                        <a class="btn btn-sm btn-soft-primary" href="{{ route('admin.products.index') }}">See all</a>
                    </x-slot:actions>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between"><span>Active</span><strong data-stat="activeProducts">{{ $totals['activeProducts'] }}</strong></li>
                        <li class="list-group-item d-flex justify-content-between"><span>Hidden</span><strong data-stat="hiddenProducts">{{ $totals['hiddenProducts'] }}</strong></li>
                        <li class="list-group-item d-flex justify-content-between"><span>Featured</span><strong data-stat="featuredProducts">{{ $totals['featuredProducts'] }}</strong></li>
                    </ul>
                </x-admin.card>
            </div>
        </div>
    </div>
</x-admin.layout>
