<x-admin.layout title="Certificates">
    <x-slot:header>
        <x-admin.page-header title="Certificates" description="Certificate PDFs. The first {{ $homeLimit }} active ones appear on the home page; all active ones on the Certificates page.">
            <x-admin.button :href="route('admin.certificates-page.edit')" variant="secondary" icon="image">Page banner</x-admin.button>
            <x-admin.button :href="route('admin.certificates.create')" icon="plus">Add certificate</x-admin.button>
        </x-admin.page-header>
    </x-slot:header>

    <div class="card">
        @if ($certificates->isEmpty())
            <x-admin.empty-state icon="award" title="No certificates yet" text="Upload ISO or government certificates as PDF. The home page section stays hidden until at least one is active.">
                <x-admin.button :href="route('admin.certificates.create')" icon="plus">Add certificate</x-admin.button>
            </x-admin.empty-state>
        @else
            <div class="table-responsive-stack">
                <table class="table table-hover mb-0 data-table">
                    <thead>
                        <tr>
                            <th scope="col">Certificate</th>
                            <th scope="col">Status</th>
                            <th scope="col">Order</th>
                            <th scope="col">PDF</th>
                            <th scope="col"><span class="visually-hidden">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($homeCount = 0)
                        @foreach ($certificates as $certificate)
                            @php($onHome = $certificate->is_active && ++$homeCount <= $homeLimit)
                            <tr>
                                <td data-label="Certificate">
                                    <a class="item-cell" href="{{ route('admin.certificates.edit', $certificate) }}">
                                        <span class="item-cell__thumb">
                                            @if ($coverUrl = $certificate->coverUrl())
                                                <img src="{{ $coverUrl }}" alt="" loading="lazy">
                                            @else
                                                <x-admin.icon name="file" class="icon-20" />
                                            @endif
                                        </span>
                                        <span class="item-cell__text">
                                            <span class="item-cell__title">{{ $certificate->name }}</span>
                                            @if ($certificate->issuer)
                                                <span class="item-cell__meta">{{ $certificate->issuer }}</span>
                                            @endif
                                        </span>
                                    </a>
                                </td>
                                <td data-label="Status">
                                    <span class="badge-group">
                                        @if ($certificate->is_active)
                                            <x-admin.badge variant="success">Active</x-admin.badge>
                                        @else
                                            <x-admin.badge>Hidden</x-admin.badge>
                                        @endif
                                        @if ($onHome)
                                            <x-admin.badge variant="info">On home page</x-admin.badge>
                                        @endif
                                    </span>
                                </td>
                                <td data-label="Order">{{ $certificate->sort_order }}</td>
                                <td data-label="PDF"><a href="{{ $certificate->fileUrl() }}" target="_blank" rel="noopener">Open<span class="visually-hidden"> {{ $certificate->name }} (PDF, new tab)</span></a></td>
                                <td class="data-table__actions">
                                    <a class="btn btn-sm btn-icon btn-soft-primary" href="{{ route('admin.certificates.edit', $certificate) }}" title="Edit" aria-label="Edit {{ $certificate->name }}"><x-admin.icon name="edit" class="icon-18" /></a>
                                    <x-admin.delete-button :action="route('admin.certificates.destroy', $certificate)" confirm="Delete the certificate &quot;{{ $certificate->name }}&quot; and its PDF?" label="Delete {{ $certificate->name }}" icon-only />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-admin.layout>
