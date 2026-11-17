/**
 * Add/remove rows in a repeatable field group (product specifications, menu
 * items). New rows are cloned from the first row; field names are renumbered
 * as <data-repeater-name>[index][field] (ids <data-repeater-id-prefix>-index-field)
 * so the submitted order matches the order on screen.
 */
function renumber(rowsContainer, name, idPrefix) {
    rowsContainer.querySelectorAll('[data-repeater-row]').forEach((row, index) => {
        row.querySelectorAll('[data-repeater-field]').forEach((input) => {
            const field = input.dataset.repeaterField;
            const id = `${idPrefix}-${index}-${field}`;

            input.name = `${name}[${index}][${field}]`;
            input.id = id;
            input.previousElementSibling?.setAttribute('for', id);
        });
    });
}

export function initRepeaters() {
    document.querySelectorAll('[data-repeater]').forEach((repeater) => {
        const rows = repeater.querySelector('[data-repeater-rows]');
        const addButton = repeater.querySelector('[data-repeater-add]');
        const max = Number(repeater.dataset.repeaterMax) || Infinity;
        const name = repeater.dataset.repeaterName || 'specifications';
        const idPrefix = repeater.dataset.repeaterIdPrefix || 'spec';
        const template = rows.querySelector('[data-repeater-row]').cloneNode(true);

        const updateAddButton = () => {
            addButton.disabled = rows.children.length >= max;
        };

        addButton.addEventListener('click', () => {
            const row = template.cloneNode(true);

            row.querySelectorAll('input').forEach((input) => {
                input.value = '';
                input.classList.remove('is-invalid');
            });
            row.querySelectorAll('.field__error').forEach((error) => error.remove());

            rows.append(row);
            renumber(rows, name, idPrefix);
            updateAddButton();
            row.querySelector('input')?.focus();
        });

        rows.addEventListener('click', (event) => {
            const removeButton = event.target.closest('[data-repeater-remove]');

            if (!removeButton) {
                return;
            }

            const row = removeButton.closest('[data-repeater-row]');

            if (rows.children.length === 1) {
                row.querySelectorAll('input').forEach((input) => {
                    input.value = '';
                });
            } else {
                row.remove();
            }

            renumber(rows, name, idPrefix);
            updateAddButton();
            addButton.focus();
        });

        updateAddButton();
    });
}
