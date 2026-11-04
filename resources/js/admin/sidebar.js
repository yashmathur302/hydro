/**
 * Off-canvas sidebar for screens narrower than the desktop breakpoint.
 * Handles open/close buttons, the backdrop, the Escape key and focus return.
 */
const DESKTOP_QUERY = window.matchMedia('(min-width: 1024px)');

export function initSidebar() {
    const sidebar = document.querySelector('[data-sidebar]');
    const openButton = document.querySelector('[data-sidebar-open]');

    if (!sidebar || !openButton) {
        return;
    }

    const backdrop = document.querySelector('.sidebar-backdrop');
    const closeTriggers = document.querySelectorAll('[data-sidebar-close]');

    const setOpen = (isOpen) => {
        sidebar.classList.toggle('is-open', isOpen);
        openButton.setAttribute('aria-expanded', String(isOpen));
        backdrop.hidden = !isOpen;
        document.body.classList.toggle('is-scroll-locked', isOpen);

        if (isOpen) {
            sidebar.querySelector('a, button')?.focus();
        }
    };

    const close = () => {
        if (!sidebar.classList.contains('is-open')) {
            return;
        }

        setOpen(false);
        openButton.focus();
    };

    openButton.addEventListener('click', () => setOpen(true));
    closeTriggers.forEach((trigger) => trigger.addEventListener('click', close));

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            close();
        }
    });

    DESKTOP_QUERY.addEventListener('change', (event) => {
        if (event.matches) {
            setOpen(false);
        }
    });
}
