{{--
    Shared confirmation dialog (native <dialog>). Forms opt in with
    data-confirm="message" and optionally data-confirm-title="title".
    See resources/js/admin/confirm-dialog.js.
--}}
<dialog class="confirm-dialog" data-confirm-dialog aria-labelledby="confirm-dialog-title" aria-describedby="confirm-dialog-message">
    <form method="dialog" class="confirm-dialog__box">
        <span class="confirm-dialog__icon" aria-hidden="true"><x-admin.icon name="trash" class="icon-24" /></span>
        <h2 class="confirm-dialog__title" id="confirm-dialog-title" data-confirm-dialog-title>Delete this item?</h2>
        <p class="confirm-dialog__message" id="confirm-dialog-message" data-confirm-message></p>
        <div class="confirm-dialog__actions">
            <button type="submit" value="cancel" class="btn btn-light" autofocus>Cancel</button>
            <button type="submit" value="confirm" class="btn btn-danger">Delete</button>
        </div>
    </form>
</dialog>
