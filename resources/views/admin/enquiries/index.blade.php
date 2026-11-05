@php
    use App\Enums\EnquiryStatus;
    use App\Enums\EnquiryType;
    use App\Http\Controllers\Admin\EnquiryController;

    $indexRoute = route(EnquiryController::indexRouteFor($type));
    $statusOptions = collect(EnquiryStatus::cases())->mapWithKeys(fn (EnquiryStatus $status) => [$status->value => $status->label()]);
    $isFiltered = $filters['q'] !== null || $filters['status'] !== null;
@endphp

<x-admin.layout :title="$type->pluralLabel()">
    <x-admin.page-header :title="$type->pluralLabel()" :description="$type === EnquiryType::Product ? 'Enquiries sent from product pages.' : 'Messages sent from the Contact Us page.'" />

    <x-admin.filters :action="$indexRoute" :search="$filters['q']" placeholder="Search name, phone, email or company" :active="$isFiltered">
        <x-admin.filter-select name="status" label="Status" :options="$statusOptions" :value="$filters['status']?->value" placeholder="Any status" />
    </x-admin.filters>

    <div class="panel__box panel__box--flush">
        @if ($enquiries->isEmpty())
            <x-admin.empty-state :icon="$type === EnquiryType::Product ? 'inbox' : 'mail'" :title="$isFiltered ? 'Nothing matches your search' : 'No '.mb_strtolower($type->pluralLabel()).' yet'" :text="$isFiltered ? 'Try a different search or status.' : 'New messages from the website will appear here and are emailed to you.'" />
        @else
            <table class="data-table data-table--inbox">
                <thead>
                    <tr>
                        <th scope="col">From</th>
                        <th scope="col">{{ $type === EnquiryType::Product ? 'Product' : 'Subject' }}</th>
                        <th scope="col">Status</th>
                        <th scope="col">Received</th>
                        <th scope="col"><span class="visually-hidden">Actions</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($enquiries as $enquiry)
                        <tr @class(['is-unread' => $enquiry->isUnread()])>
                            <td data-label="From">
                                <a class="item-cell" href="{{ route('admin.enquiries.show', $enquiry) }}">
                                    <span class="avatar avatar--small avatar--soft" aria-hidden="true">{{ mb_strtoupper(mb_substr($enquiry->name, 0, 1)) }}</span>
                                    <span class="item-cell__text">
                                        <span class="item-cell__title">
                                            {{ $enquiry->name }}
                                            @if ($enquiry->isUnread())<span class="unread-dot"><span class="visually-hidden">(unread)</span></span>@endif
                                        </span>
                                        <span class="item-cell__meta">{{ $enquiry->phone }}{{ $enquiry->company ? ' · '.$enquiry->company : '' }}</span>
                                    </span>
                                </a>
                            </td>
                            <td data-label="{{ $type === EnquiryType::Product ? 'Product' : 'Subject' }}">
                                {{ ($type === EnquiryType::Product ? $enquiry->product_name : $enquiry->subject) ?? '—' }}
                            </td>
                            <td data-label="Status"><x-admin.badge :variant="$enquiry->status->badgeVariant()">{{ $enquiry->status->label() }}</x-admin.badge></td>
                            <td data-label="Received"><time datetime="{{ $enquiry->created_at->toIso8601String() }}" title="{{ $enquiry->created_at->format('d M Y, h:i A') }}">{{ $enquiry->created_at->diffForHumans() }}</time></td>
                            <td class="data-table__actions">
                                <a class="icon-action" href="{{ route('admin.enquiries.show', $enquiry) }}" title="Open" aria-label="Open message from {{ $enquiry->name }}"><x-admin.icon name="arrow-right" /></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{ $enquiries->links('admin.partials.pagination') }}
</x-admin.layout>
