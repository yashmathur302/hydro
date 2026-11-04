/**
 * Sidebar behaviour.
 * - Below the desktop breakpoint: off-canvas drawer with backdrop, Escape key
 *   and focus return.
 * - On desktop: collapsible to an icon rail; the choice is remembered.
 */
const DESKTOP_QUERY = window.matchMedia('(min-width: 1024px)');
const COLLAPSED_CLASS = 'is-sidebar-collapsed';
const STORAGE_KEY = 'admin.sidebar';

function rememberCollapsed(isCollapsed) {
    try {
        localStorage.setItem(STORAGE_KEY, isCollapsed ? 'collapsed' : 'expanded');
    } catch {
        // Storage can be unavailable (private mode); the preference is optional.
    }
}

function initCollapseToggle() {
    const button = document.querySelector('[data-sidebar-collapse]');

    if (!button) {
        return;
    }

    const root = document.documentElement;

    const sync = () => {
        const isCollapsed = root.classList.contains(COLLAPSED_CLASS);

        button.setAttribute('aria-expanded', String(!isCollapsed));
        button.setAttribute('aria-label', isCollapsed ? 'Expand sidebar' : 'Collapse sidebar');
    };

    button.addEventListener('click', () => {
        const isCollapsed = root.classList.toggle(COLLAPSED_CLASS);

        rememberCollapsed(isCollapsed);
        sync();
    });

    sync();
}

function initDrawer() {
    const sidebar = document.querySelector('[data-sidebar]');
    const openButton = document.querySelector('[data-sidebar-open]');

    if (!sidebar || !openButton) {
        return;
    }

    const backdrop = document.querySelector('.sidebar-backdrop');

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
    document.querySelectorAll('[data-sidebar-close]').forEach((trigger) => trigger.addEventListener('click', close));

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

export function initSidebar() {
    initDrawer();
    initCollapseToggle();
}
