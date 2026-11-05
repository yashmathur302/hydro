/**
 * Live "used / max" character counter for fields rendered with the
 * "counter" option (e.g. meta title and description).
 */
export function initCharacterCounters() {
    document.querySelectorAll('[data-counter-for]').forEach((counter) => {
        const field = document.getElementById(counter.dataset.counterFor);
        const max = Number(field?.getAttribute('maxlength'));

        if (!field || !max) {
            return;
        }

        const update = () => {
            const length = field.value.length;

            counter.textContent = `${length} / ${max}`;
            counter.classList.toggle('is-over', length >= max);
        };

        field.addEventListener('input', update);
        update();
    });
}
