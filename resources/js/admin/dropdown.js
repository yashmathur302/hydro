/**
 * Minimal dropdown (the account menu in the top bar), replacing Bootstrap's
 * JavaScript. Opens on click, closes on outside click or Escape.
 */
export function initDropdowns() {
    document.querySelectorAll('[data-dropdown]').forEach((dropdown) => {
        const toggle = dropdown.querySelector('[data-dropdown-toggle]');
        const menu = dropdown.querySelector('[data-dropdown-menu]');

        const setOpen = (open) => {
            menu.classList.toggle('show', open);
            toggle.setAttribute('aria-expanded', String(open));
        };

        toggle.addEventListener('click', (event) => {
            event.stopPropagation();
            setOpen(!menu.classList.contains('show'));
        });

        document.addEventListener('click', (event) => {
            if (!dropdown.contains(event.target)) {
                setOpen(false);
            }
        });

        dropdown.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && menu.classList.contains('show')) {
                setOpen(false);
                toggle.focus();
            }
        });
    });
}
