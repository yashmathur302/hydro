<x-admin.layout title="Industries">
    <x-admin.page-header title="Industries" description="The industries you serve, shown as cards on the home page.">
        <x-admin.button :href="route('admin.industries.create')" icon="plus">Add industry</x-admin.button>
    </x-admin.page-header>

    <div class="panel__box panel__box--flush">
        @if ($industries->isEmpty())
            <x-admin.empty-state icon="factory" title="No industries yet" text="Add the industries you serve, such as Oil & Gas, Water or Refineries. The home page section stays hidden until at least one is active.">
                <x-admin.button :href="route('admin.industries.create')" icon="plus">Add industry</x-admin.button>
            </x-admin.empty-state>
        @else
            <table class="data-table">
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
                                <a class="icon-action" href="{{ route('admin.industries.edit', $industry) }}" title="Edit" aria-label="Edit {{ $industry->name }}"><x-admin.icon name="edit" /></a>
                                <x-admin.delete-button :action="route('admin.industries.destroy', $industry)" confirm="Delete the industry &quot;{{ $industry->name }}&quot;?" label="Delete {{ $industry->name }}" icon-only />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-admin.layout>
