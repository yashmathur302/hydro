/**
 * Hope UI sidebar behaviour without the template's jQuery bundle.
 * - Desktop (1200px+): the toggle switches the slim "mini" menu; the choice is remembered.
 * - Smaller screens: the menu is hidden ("sidebar-mini" moves it off-screen) and the
 *   toggle shows it as an overlay with a backdrop; Escape or the backdrop closes it.
 * The initial state is applied by an inline script right after the sidebar markup.
 */
const DESKTOP_QUERY = window.matchMedia('(min-width: 1200px)');
const STORAGE_KEY = 'admin.sidebar';

function remember(isCollapsed) {
    try {
        localStorage.setItem(STORAGE_KEY, isCollapsed ? 'collapsed' : 'expanded');
    } catch {
        // Storage can be unavailable (private mode); the preference is optional.
    }
}

function remembered() {
    try {
        return localStorage.getItem(STORAGE_KEY) === 'collapsed';
    } catch {
        return false;
    }
}

export function initSidebar() {
    const sidebar = document.querySelector('[data-sidebar]');

    if (!sidebar) {
        return;
    }

    const backdrop = document.querySelector('.sidebar-backdrop');
    const toggles = document.querySelectorAll('[data-sidebar-toggle]');

    const isOverlayOpen = () => !DESKTOP_QUERY.matches && !sidebar.classList.contains('sidebar-mini');

    const sync = () => {
        const expanded = !sidebar.classList.contains('sidebar-mini');

        toggles.forEach((toggle) => toggle.setAttribute('aria-expanded', String(expanded)));
        backdrop.hidden = !isOverlayOpen();
        document.body.classList.toggle('overflow-hidden', isOverlayOpen());
    };

    const setCollapsed = (collapsed) => {
        sidebar.classList.toggle('sidebar-mini', collapsed);

        if (DESKTOP_QUERY.matches) {
            remember(collapsed);
        } else if (!collapsed) {
            sidebar.querySelector('a, button')?.focus();
        }

        sync();
    };

    toggles.forEach((toggle) => toggle.addEventListener('click', () => {
        setCollapsed(!sidebar.classList.contains('sidebar-mini'));
    }));

    document.querySelectorAll('[data-sidebar-close]').forEach((element) => {
        element.addEventListener('click', () => setCollapsed(true));
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && isOverlayOpen()) {
            setCollapsed(true);
        }
    });

    DESKTOP_QUERY.addEventListener('change', (event) => {
        sidebar.classList.toggle('sidebar-mini', event.matches ? remembered() : true);
        sync();
    });

    sync();
}
