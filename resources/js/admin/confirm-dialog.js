/**
 * Asks for confirmation before submitting any form marked with data-confirm
 * (message) and optionally data-confirm-title, using the shared <dialog>. Falls back to window.confirm if <dialog> is unsupported.
 */
export function initConfirmDialog() {
    const dialog = document.querySelector('[data-confirm-dialog]');
    const message = dialog?.querySelector('[data-confirm-message]');
    const title = dialog?.querySelector('[data-confirm-dialog-title]');
    const defaultTitle = title?.textContent ?? '';
    let pendingForm = null;

    document.addEventListener('submit', (event) => {
        const form = event.target;

        if (!(form instanceof HTMLFormElement) || !form.dataset.confirm || form.dataset.confirmed === 'true') {
            return;
        }

        event.preventDefault();

        if (!dialog || typeof dialog.showModal !== 'function') {
            if (window.confirm(form.dataset.confirm)) {
                submitConfirmed(form);
            }

            return;
        }

        pendingForm = form;
        title.textContent = form.dataset.confirmTitle || defaultTitle;
        message.textContent = form.dataset.confirm;
        dialog.showModal();
    });

    dialog?.addEventListener('close', () => {
        if (dialog.returnValue === 'confirm' && pendingForm) {
            submitConfirmed(pendingForm);
        }

        pendingForm = null;
        dialog.returnValue = '';
    });
}

function submitConfirmed(form) {
    form.dataset.confirmed = 'true';
    form.requestSubmit();
}
