/**
 * Mobile menu drawer: open/close buttons, backdrop, Escape key, focus return,
 * and automatic close when the screen grows to the desktop layout.
 */
const DESKTOP_QUERY = window.matchMedia('(min-width: 1200px)');

export function initNavigation() {
    const nav = document.querySelector('[data-site-nav]');
    const openButton = document.querySelector('[data-site-nav-open]');

    if (!nav || !openButton) {
        return;
    }

    const backdrop = document.querySelector('.site-nav-backdrop');

    const setOpen = (isOpen) => {
        nav.classList.toggle('is-open', isOpen);
        openButton.setAttribute('aria-expanded', String(isOpen));
        backdrop.hidden = !isOpen;
        document.body.classList.toggle('is-scroll-locked', isOpen);

        if (isOpen) {
            nav.querySelector('a, button')?.focus();
        }
    };

    const close = () => {
        if (!nav.classList.contains('is-open')) {
            return;
        }

        setOpen(false);
        openButton.focus();
    };

    openButton.addEventListener('click', () => setOpen(true));
    document.querySelectorAll('[data-site-nav-close]').forEach((element) => element.addEventListener('click', close));

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
