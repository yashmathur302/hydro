/**
 * Shows the back-to-top button once the visitor has scrolled past the first screen.
 */
export function initBackToTop() {
    const button = document.querySelector('[data-back-to-top]');

    if (!button) {
        return;
    }

    let ticking = false;

    const update = () => {
        button.classList.toggle('is-visible', window.scrollY > window.innerHeight * 0.6);
        ticking = false;
    };

    window.addEventListener('scroll', () => {
        if (!ticking) {
            ticking = true;
            window.requestAnimationFrame(update);
        }
    }, { passive: true });

    update();
}
