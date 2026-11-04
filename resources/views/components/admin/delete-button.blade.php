{{-- DELETE form behind a confirmation dialog. Works without JavaScript via the native confirm fallback. --}}
@props(['action', 'confirm', 'label' => 'Delete', 'iconOnly' => false])

<form method="POST" action="{{ $action }}" class="inline-form" data-confirm="{{ $confirm }}">
    @csrf
    @method('DELETE')
    @if ($iconOnly)
        <button type="submit" class="icon-action icon-action--danger" title="{{ $label }}" aria-label="{{ $label }}">
            <x-admin.icon name="trash" />
        </button>
    @else
        <button type="submit" class="button button--danger">
            <x-admin.icon name="trash" />
            <span>{{ $label }}</span>
        </button>
    @endif
</form>
