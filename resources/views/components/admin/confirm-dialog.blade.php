{{-- Shared confirmation dialog (native <dialog>, styled as a Hope UI modal). See resources/js/admin/confirm-dialog.js. --}}
<dialog class="confirm-dialog" data-confirm-dialog aria-labelledby="confirm-dialog-title" aria-describedby="confirm-dialog-message">
    <form method="dialog" class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title h5" id="confirm-dialog-title">Are you sure?</h2>
        </div>
        <div class="modal-body">
            <p class="mb-0" id="confirm-dialog-message" data-confirm-message></p>
        </div>
        <div class="modal-footer">
            <button type="submit" value="cancel" class="btn btn-outline-secondary" autofocus>Cancel</button>
            <button type="submit" value="confirm" class="btn btn-danger">Yes, delete</button>
        </div>
    </form>
</dialog>
