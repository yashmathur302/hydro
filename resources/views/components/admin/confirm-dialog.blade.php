{{-- Single shared confirmation dialog used by every delete form (see resources/js/admin/confirm-dialog.js). --}}
<dialog class="dialog" data-confirm-dialog aria-labelledby="confirm-dialog-title" aria-describedby="confirm-dialog-message">
    <form method="dialog" class="dialog__body">
        <span class="dialog__icon"><x-admin.icon name="trash" /></span>
        <h2 class="dialog__title" id="confirm-dialog-title">Are you sure?</h2>
        <p class="dialog__message" id="confirm-dialog-message" data-confirm-message></p>
        <div class="dialog__actions">
            <button type="submit" value="cancel" class="button button--secondary" autofocus>Cancel</button>
            <button type="submit" value="confirm" class="button button--danger">Yes, delete</button>
        </div>
    </form>
</dialog>
