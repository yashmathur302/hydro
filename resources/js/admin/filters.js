/**
 * List filters: choosing an option in a filter dropdown applies it at once
 * (the search box still uses its Search button or the Enter key).
 */
export function initFilters() {
    document.querySelectorAll('[data-auto-submit]').forEach((select) => {
        select.addEventListener('change', () => {
            select.form?.requestSubmit();
        });
    });
}
