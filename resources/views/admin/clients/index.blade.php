<x-admin.layout title="Clients">
    <x-slot:header>
        <x-admin.page-header title="Clients" description="Client logos shown on the home page.">
            <x-admin.button :href="route('admin.clients.create')" icon="plus">Add client</x-admin.button>
        </x-admin.page-header>
    </x-slot:header>

    <div class="card">
        @if ($clients->isEmpty())
            <x-admin.empty-state icon="handshake" title="No clients yet" text="Add client logos to show them on the home page. The section stays hidden until at least one is active.">
                <x-admin.button :href="route('admin.clients.create')" icon="plus">Add client</x-admin.button>
            </x-admin.empty-state>
        @else
            <div class="table-responsive-stack">
                <table class="table table-hover mb-0 data-table">
                    <thead>
                        <tr>
                            <th scope="col">Client</th>
                            <th scope="col">Status</th>
                            <th scope="col">Order</th>
                            <th scope="col"><span class="visually-hidden">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clients as $client)
                            <tr>
                                <td data-label="Client">
                                    <a class="item-cell" href="{{ route('admin.clients.edit', $client) }}">
                                        <span class="item-cell__thumb item-cell__thumb--logo"><img src="{{ $client->logoUrl() }}" alt="" loading="lazy"></span>
                                        <span class="item-cell__text">
                                            <span class="item-cell__title">{{ $client->name }}</span>
                                            @if ($client->website_url)
                                                <span class="item-cell__meta">{{ $client->website_url }}</span>
                                            @endif
                                        </span>
                                    </a>
                                </td>
                                <td data-label="Status">
                                    @if ($client->is_active)
                                        <x-admin.badge variant="success">Active</x-admin.badge>
                                    @else
                                        <x-admin.badge>Hidden</x-admin.badge>
                                    @endif
                                </td>
                                <td data-label="Order">{{ $client->sort_order }}</td>
                                <td class="data-table__actions">
                                    <a class="btn btn-sm btn-icon btn-soft-primary" href="{{ route('admin.clients.edit', $client) }}" title="Edit" aria-label="Edit {{ $client->name }}"><x-admin.icon name="edit" class="icon-18" /></a>
                                    <x-admin.delete-button :action="route('admin.clients.destroy', $client)" confirm="Delete the client &quot;{{ $client->name }}&quot;?" label="Delete {{ $client->name }}" icon-only />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-admin.layout>
