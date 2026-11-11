<x-admin.layout title="Products">
    <x-slot:header>
        <x-admin.page-header title="Products" description="Everything shown in the product catalogue on the website.">
            <x-admin.button :href="route('admin.products.create')" icon="plus">Add product</x-admin.button>
        </x-admin.page-header>
    </x-slot:header>

    @if ($categories->isEmpty())
        <div class="alert alert-primary d-flex flex-wrap align-items-center gap-2">
            <x-admin.icon name="folder" class="icon-20 flex-shrink-0" />
            <p class="mb-0 flex-grow-1">Create a category first. Every product belongs to one category.</p>
            <x-admin.button :href="route('admin.categories.create')" variant="secondary" size="small" icon="plus">Add category</x-admin.button>
        </div>
    @endif

    <div class="card">
        <x-admin.filters :action="route('admin.products.index')" :search="$filters['q']" placeholder="Search by name" :active="array_filter($filters) !== []">
            <x-admin.filter-select name="category" label="Category" :options="$categories" :value="$filters['category']" placeholder="All categories" />
            <x-admin.filter-select name="status" label="Status" :options="['active' => 'Active', 'inactive' => 'Hidden', 'featured' => 'Featured']" :value="$filters['status']" placeholder="Any status" />
        </x-admin.filters>

        @if ($products->isEmpty())
            <x-admin.empty-state icon="box" title="No products found" :text="array_filter($filters) ? 'Try a different search or filter.' : 'Add your first product to start the catalogue.'">
                @unless (array_filter($filters))
                    <x-admin.button :href="route('admin.products.create')" icon="plus">Add product</x-admin.button>
                @endunless
            </x-admin.empty-state>
        @else
            <div class="table-responsive-stack">
                <table class="table table-hover mb-0 data-table">
                    <thead>
                        <tr>
                            <th scope="col">Product</th>
                            <th scope="col">Category</th>
                            <th scope="col">Status</th>
                            <th scope="col">Updated</th>
                            <th scope="col"><span class="visually-hidden">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td data-label="Product">
                                    <a class="item-cell" href="{{ route('admin.products.edit', $product) }}">
                                        <span class="item-cell__thumb">
                                            @if ($product->primaryImage)
                                                <img src="{{ $product->primaryImage->thumbnailUrl() }}" alt="" width="48" height="48" loading="lazy">
                                            @else
                                                <x-admin.icon name="image" />
                                            @endif
                                        </span>
                                        <span class="item-cell__text">
                                            <span class="item-cell__title">{{ $product->name }}</span>
                                            <span class="item-cell__meta">/{{ $product->slug }}</span>
                                        </span>
                                    </a>
                                </td>
                                <td data-label="Category">{{ $product->category->name }}</td>
                                <td data-label="Status">
                                    <span class="badge-group">
                                        @if ($product->is_active)
                                            <x-admin.badge variant="success">Active</x-admin.badge>
                                        @else
                                            <x-admin.badge>Hidden</x-admin.badge>
                                        @endif
                                        @if ($product->is_featured)
                                            <x-admin.badge variant="info">Featured</x-admin.badge>
                                        @endif
                                    </span>
                                </td>
                                <td data-label="Updated"><time datetime="{{ $product->updated_at->toIso8601String() }}">{{ $product->updated_at->format('d M Y') }}</time></td>
                                <td class="data-table__actions">
                                    <a class="btn btn-sm btn-icon btn-soft-primary" href="{{ route('admin.products.edit', $product) }}" title="Edit" aria-label="Edit {{ $product->name }}"><x-admin.icon name="edit" class="icon-18" /></a>
                                    <x-admin.delete-button :action="route('admin.products.destroy', $product)" confirm="Delete &quot;{{ $product->name }}&quot;? Its images and brochure will be removed too." label="Delete {{ $product->name }}" icon-only />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{ $products->links('admin.partials.pagination') }}
</x-admin.layout>
