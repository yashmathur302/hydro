/**
 * Marks the sticky segmented control while it is pinned to the top of the
 * screen, so CSS can give it a solid strip behind it. The bar sticks at
 * top: -1px, so it is "stuck" exactly when it is no longer fully visible.
 */
function watchStuck(nav) {
    const observer = new IntersectionObserver(
        ([entry]) => nav.classList.toggle('is-stuck', entry.intersectionRatio < 1 && entry.boundingClientRect.top < 1),
        { threshold: [1] },
    );

    observer.observe(nav);
}

/**
 * Highlights the segmented-control link of the section currently in view
 * (used on the settings and home page editors).
 */
export function initSectionNav() {
    const links = [...document.querySelectorAll('[data-section-link]')];

    if (links.length === 0 || !('IntersectionObserver' in window)) {
        return;
    }

    document.querySelectorAll('.segmented').forEach(watchStuck);

    const linkById = new Map(links.map((link) => [link.hash.slice(1), link]));

    const setActive = (id) => {
        links.forEach((link) => {
            const isActive = link === linkById.get(id);

            link.classList.toggle('is-active', isActive);
            link.toggleAttribute('aria-current', isActive);
        });
    };

    const observer = new IntersectionObserver(
        (entries) => {
            const visible = entries.filter((entry) => entry.isIntersecting);

            if (visible.length > 0) {
                setActive(visible[0].target.id);
            }
        },
        { rootMargin: '-25% 0px -65% 0px' },
    );

    linkById.forEach((_link, id) => {
        const section = document.getElementById(id);

        if (section) {
            observer.observe(section);
        }
    });

    setActive(links[0].hash.slice(1));
}
