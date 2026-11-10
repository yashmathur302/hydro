<x-admin.layout title="Categories">
    <x-slot:header>
        <x-admin.page-header title="Categories" description="Group products for the catalogue. A category with products cannot be deleted.">
            <x-admin.button :href="route('admin.categories.create')" icon="plus">Add category</x-admin.button>
        </x-admin.page-header>
    </x-slot:header>

    <div class="card">
        @if ($categories->isEmpty())
            <x-admin.empty-state icon="folder" title="No categories yet" text="Create categories such as “Pressure Gauges” or “Valves”, then add products to them.">
                <x-admin.button :href="route('admin.categories.create')" icon="plus">Add category</x-admin.button>
            </x-admin.empty-state>
        @else
            <div class="table-responsive-stack">
                <table class="table table-hover mb-0 data-table">
                    <thead>
                        <tr>
                            <th scope="col">Category</th>
                            <th scope="col">Products</th>
                            <th scope="col">Status</th>
                            <th scope="col">Order</th>
                            <th scope="col"><span class="visually-hidden">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td data-label="Category">
                                    <a class="item-cell" href="{{ route('admin.categories.edit', $category) }}">
                                        <span class="item-cell__thumb">
                                            @if ($category->image_path)
                                                <img src="{{ Storage::disk('public')->url($category->image_path) }}" alt="" width="48" height="48" loading="lazy">
                                            @else
                                                <x-admin.icon name="folder" />
                                            @endif
                                        </span>
                                        <span class="item-cell__text">
                                            <span class="item-cell__title">{{ $category->name }}</span>
                                            <span class="item-cell__meta">/{{ $category->slug }}</span>
                                        </span>
                                    </a>
                                </td>
                                <td data-label="Products">
                                    <a href="{{ route('admin.products.index', ['category' => $category->id]) }}">{{ $category->products_count }}</a>
                                </td>
                                <td data-label="Status">
                                    @if ($category->is_active)
                                        <x-admin.badge variant="success">Active</x-admin.badge>
                                    @else
                                        <x-admin.badge>Hidden</x-admin.badge>
                                    @endif
                                </td>
                                <td data-label="Order">{{ $category->sort_order }}</td>
                                <td class="data-table__actions">
                                    <a class="btn btn-sm btn-icon btn-soft-primary" href="{{ route('admin.categories.edit', $category) }}" title="Edit" aria-label="Edit {{ $category->name }}"><x-admin.icon name="edit" class="icon-18" /></a>
                                    <x-admin.delete-button :action="route('admin.categories.destroy', $category)" confirm="Delete the category &quot;{{ $category->name }}&quot;?" label="Delete {{ $category->name }}" icon-only />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-admin.layout>
