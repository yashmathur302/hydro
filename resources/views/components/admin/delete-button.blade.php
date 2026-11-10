{{-- DELETE form behind the shared confirmation dialog. --}}
@props(['action', 'confirm', 'label' => 'Delete', 'iconOnly' => false])

<form method="POST" action="{{ $action }}" class="d-inline" data-confirm="{{ $confirm }}">
    @csrf
    @method('DELETE')
    @if ($iconOnly)
        <button type="submit" class="btn btn-sm btn-icon btn-soft-danger" title="{{ $label }}" aria-label="{{ $label }}">
            <span class="btn-inner"><x-admin.icon name="trash" class="icon-18" /></span>
        </button>
    @else
        <button type="submit" class="btn btn-danger d-inline-flex align-items-center gap-2">
            <x-admin.icon name="trash" class="icon-18" />
            <span>{{ $label }}</span>
        </button>
    @endif
</form>
