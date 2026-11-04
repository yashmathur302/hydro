/**
 * Highlights the segmented-control link of the section currently in view
 * (used on the settings page).
 */
export function initSectionNav() {
    const links = [...document.querySelectorAll('[data-section-link]')];

    if (links.length === 0 || !('IntersectionObserver' in window)) {
        return;
    }

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
