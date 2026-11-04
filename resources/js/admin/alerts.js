/**
 * Dismissible flash messages and focus handling for validation summaries.
 */
export function initDismissibleAlerts() {
    document.querySelectorAll('[data-dismissible]').forEach((alert) => {
        alert.querySelector('[data-dismiss]')?.addEventListener('click', () => alert.remove());
    });
}

/**
 * Moves focus to the first invalid field after a failed submit, so keyboard
 * and screen reader users land where the problem is.
 */
export function focusErrorSummary() {
    const firstInvalid = document.querySelector('[aria-invalid="true"]');

    firstInvalid?.focus();
}
