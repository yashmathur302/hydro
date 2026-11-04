<x-admin.layout title="Static pages">
    <x-admin.page-header title="Static pages" description="Text pages such as the privacy policy and terms and conditions.">
        <x-admin.button :href="route('admin.pages.create')" icon="plus">Add page</x-admin.button>
    </x-admin.page-header>

    <div class="panel__box panel__box--flush">
        @if ($pages->isEmpty())
            <x-admin.empty-state icon="file" title="No pages yet">
                <x-admin.button :href="route('admin.pages.create')" icon="plus">Add page</x-admin.button>
            </x-admin.empty-state>
        @else
            <table class="data-table">
                <thead>
                    <tr>
                        <th scope="col">Page</th>
                        <th scope="col">Status</th>
                        <th scope="col">Updated</th>
                        <th scope="col"><span class="visually-hidden">Actions</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pages as $page)
                        <tr>
                            <td data-label="Page">
                                <a class="item-cell" href="{{ route('admin.pages.edit', $page) }}">
                                    <span class="item-cell__thumb"><x-admin.icon name="file" /></span>
                                    <span class="item-cell__text">
                                        <span class="item-cell__title">{{ $page->title }}</span>
                                        <span class="item-cell__meta">/{{ $page->slug }}</span>
                                    </span>
                                </a>
                            </td>
                            <td data-label="Status">
                                @if ($page->is_active)
                                    <x-admin.badge variant="success">Published</x-admin.badge>
                                @else
                                    <x-admin.badge>Draft</x-admin.badge>
                                @endif
                            </td>
                            <td data-label="Updated"><time datetime="{{ $page->updated_at->toIso8601String() }}">{{ $page->updated_at->format('d M Y') }}</time></td>
                            <td class="data-table__actions">
                                <a class="icon-action" href="{{ route('admin.pages.edit', $page) }}" title="Edit" aria-label="Edit {{ $page->title }}"><x-admin.icon name="edit" /></a>
                                <x-admin.delete-button :action="route('admin.pages.destroy', $page)" confirm="Delete the page &quot;{{ $page->title }}&quot;?" label="Delete {{ $page->title }}" icon-only />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-admin.layout>
