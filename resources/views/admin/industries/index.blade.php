<x-admin.layout title="Industries">
    <x-slot:header>
        <x-admin.page-header title="Industries" description="The industries you serve, shown as cards on the home page.">
            <x-admin.button :href="route('admin.industries.create')" icon="plus">Add industry</x-admin.button>
        </x-admin.page-header>
    </x-slot:header>

    <div class="card">
        @if ($industries->isEmpty())
            <x-admin.empty-state icon="factory" title="No industries yet" text="Add the industries you serve, such as Oil & Gas, Water or Refineries. The home page section stays hidden until at least one is active.">
                <x-admin.button :href="route('admin.industries.create')" icon="plus">Add industry</x-admin.button>
            </x-admin.empty-state>
        @else
            <div class="table-responsive-stack">
                <table class="table table-hover mb-0 data-table">
                    <thead>
                        <tr>
                            <th scope="col">Industry</th>
                            <th scope="col">Status</th>
                            <th scope="col">Order</th>
                            <th scope="col"><span class="visually-hidden">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($industries as $industry)
                            <tr>
                                <td data-label="Industry">
                                    <a class="item-cell" href="{{ route('admin.industries.edit', $industry) }}">
                                        <span class="item-cell__thumb item-cell__thumb--icon"><x-site.feature-icon :name="$industry->icon" /></span>
                                        <span class="item-cell__text">
                                            <span class="item-cell__title">{{ $industry->name }}</span>
                                            <span class="item-cell__meta">{{ $industry->description }}</span>
                                        </span>
                                    </a>
                                </td>
                                <td data-label="Status">
                                    @if ($industry->is_active)
                                        <x-admin.badge variant="success">Active</x-admin.badge>
                                    @else
                                        <x-admin.badge>Hidden</x-admin.badge>
                                    @endif
                                </td>
                                <td data-label="Order">{{ $industry->sort_order }}</td>
                                <td class="data-table__actions">
                                    <a class="btn btn-sm btn-icon btn-soft-primary" href="{{ route('admin.industries.edit', $industry) }}" title="Edit" aria-label="Edit {{ $industry->name }}"><x-admin.icon name="edit" class="icon-18" /></a>
                                    <x-admin.delete-button :action="route('admin.industries.destroy', $industry)" confirm-title="Delete this industry?" confirm="“{{ $industry->name }}” will be removed from the website. This cannot be undone." label="Delete {{ $industry->name }}" icon-only />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-admin.layout>
