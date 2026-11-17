/**
 * Counts numbers up from zero when they scroll into view.
 * The server renders the final value (e.g. "500+", "98%", "1.2m"), so the
 * text is correct without JavaScript; this only animates the number part.
 * Skipped for visitors who prefer reduced motion.
 */
const DURATION_MS = 1600;
const NUMBER_PATTERN = /^(\D*)(\d+(?:[.,]\d+)?)(.*)$/;

function animate(element) {
    const match = element.textContent.trim().match(NUMBER_PATTERN);

    if (!match) {
        return;
    }

    const [, prefix, numberText, suffix] = match;
    const decimals = numberText.includes('.') ? numberText.split('.')[1].length : 0;
    const target = parseFloat(numberText.replace(',', ''));
    const start = performance.now();

    const step = (now) => {
        const progress = Math.min((now - start) / DURATION_MS, 1);
        const eased = 1 - (1 - progress) ** 3;

        element.textContent = `${prefix}${(target * eased).toFixed(decimals)}${suffix}`;

        if (progress < 1) {
            window.requestAnimationFrame(step);
        } else {
            element.textContent = `${prefix}${numberText}${suffix}`;
        }
    };

    window.requestAnimationFrame(step);
}

export function initCounters() {
    const counters = document.querySelectorAll('[data-count-up]');
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (counters.length === 0 || reduceMotion || !('IntersectionObserver' in window)) {
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                observer.unobserve(entry.target);
                animate(entry.target);
            }
        });
    }, { threshold: 0.6 });

    counters.forEach((counter) => observer.observe(counter));
}
