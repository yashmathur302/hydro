/**
 * Add/remove rows in a repeatable field group (product specifications).
 * New rows are cloned from the first row; field names are renumbered so the
 * submitted order matches the order on screen.
 */
function renumber(rowsContainer) {
    rowsContainer.querySelectorAll('[data-repeater-row]').forEach((row, index) => {
        row.querySelectorAll('[data-repeater-field]').forEach((input) => {
            const field = input.dataset.repeaterField;
            const id = `spec-${index}-${field}`;

            input.name = `specifications[${index}][${field}]`;
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
            renumber(rows);
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

            renumber(rows);
            updateAddButton();
            addButton.focus();
        });

        updateAddButton();
    });
}
