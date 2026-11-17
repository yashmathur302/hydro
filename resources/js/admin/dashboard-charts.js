/**
 * Dashboard charts and live figures.
 * Draws small SVG charts (no chart library: the admin's Content Security Policy
 * blocks the inline styles such libraries inject) from the JSON embedded in the
 * page, then refreshes everything from the statistics endpoint every minute
 * while the tab is visible. Data is only ever written as text or SVG attributes,
 * never as HTML.
 */
const SVG_NS = 'http://www.w3.org/2000/svg';
const REFRESH_MS = 60000;
const STATUS_ORDER = ['new', 'in_progress', 'closed'];
// Below this width (in CSS pixels) the trend chart uses a narrower drawing so
// its labels stay readable on phones instead of shrinking with the SVG.
const COMPACT_CHART_WIDTH = 520;

function isCompact(figure) {
    return figure.clientWidth > 0 && figure.clientWidth < COMPACT_CHART_WIDTH;
}

function svg(tag, attributes = {}) {
    const element = document.createElementNS(SVG_NS, tag);

    Object.entries(attributes).forEach(([name, value]) => element.setAttribute(name, String(value)));

    return element;
}

function niceMax(value) {
    if (value <= 4) {
        return 4;
    }

    const magnitude = 10 ** Math.floor(Math.log10(value));
    const step = [1, 2, 5, 10].find((factor) => factor * magnitude * 4 >= value) * magnitude;

    return step * 4;
}

function drawTrend(figure, trend) {
    const compact = isCompact(figure);
    const width = compact ? 360 : 720;
    const height = compact ? 220 : 260;
    const labelEvery = compact ? 10 : 5;
    const pad = { top: 16, right: 12, bottom: 28, left: 32 };
    const innerWidth = width - pad.left - pad.right;
    const innerHeight = height - pad.top - pad.bottom;
    const max = niceMax(Math.max(1, ...trend.map((day) => Math.max(day.product, day.contact))));
    const x = (index) => pad.left + (trend.length < 2 ? 0 : (index / (trend.length - 1)) * innerWidth);
    const y = (value) => pad.top + innerHeight - (value / max) * innerHeight;

    const chart = svg('svg', { viewBox: `0 0 ${width} ${height}`, class: 'chart__svg', role: 'img', 'aria-hidden': 'true' });

    for (let step = 0; step <= 4; step += 1) {
        const value = (max / 4) * step;
        chart.append(svg('line', { x1: pad.left, x2: width - pad.right, y1: y(value), y2: y(value), class: 'chart__grid' }));
        const label = svg('text', { x: pad.left - 8, y: y(value) + 4, class: 'chart__axis', 'text-anchor': 'end' });
        label.textContent = String(Math.round(value));
        chart.append(label);
    }

    trend.forEach((day, index) => {
        if (index % labelEvery === 0 || index === trend.length - 1) {
            const label = svg('text', { x: x(index), y: height - 8, class: 'chart__axis', 'text-anchor': 'middle' });
            label.textContent = day.label;
            chart.append(label);
        }
    });

    ['contact', 'product'].forEach((series) => {
        const points = trend.map((day, index) => `${x(index)},${y(day[series])}`);
        const baseline = y(0);

        chart.append(svg('path', {
            d: `M${x(0)},${baseline} L${points.join(' L')} L${x(trend.length - 1)},${baseline} Z`,
            class: `chart__area chart__area--${series}`,
        }));
        chart.append(svg('polyline', { points: points.join(' '), class: `chart__line chart__line--${series}` }));

        trend.forEach((day, index) => {
            const dot = svg('circle', { cx: x(index), cy: y(day[series]), r: compact ? 2.5 : 3.5, class: `chart__dot chart__dot--${series}` });
            const title = svg('title');
            title.textContent = `${day.label}: ${day[series]} ${series === 'product' ? 'product enquiries' : 'contact messages'}`;
            dot.append(title);
            chart.append(dot);
        });
    });

    figure.querySelector('.chart__svg')?.remove();
    figure.prepend(chart);
}

function drawStatuses(figure, statuses) {
    const ordered = STATUS_ORDER.map((key) => statuses.find((status) => status.status === key) ?? { status: key, count: 0 });
    const total = ordered.reduce((sum, status) => sum + status.count, 0);
    const radius = 70;
    const circumference = 2 * Math.PI * radius;
    const chart = svg('svg', { viewBox: '0 0 200 200', class: 'chart__svg', role: 'img', 'aria-hidden': 'true' });

    chart.append(svg('circle', { cx: 100, cy: 100, r: radius, class: 'chart__track' }));

    let offset = 0;
    ordered.forEach((status) => {
        if (total === 0 || status.count === 0) {
            return;
        }

        const length = (status.count / total) * circumference;
        chart.append(svg('circle', {
            cx: 100,
            cy: 100,
            r: radius,
            class: `chart__slice chart__slice--${status.status}`,
            'stroke-dasharray': `${length} ${circumference - length}`,
            'stroke-dashoffset': -offset,
            transform: 'rotate(-90 100 100)',
        }));
        offset += length;
    });

    const value = svg('text', { x: 100, y: 98, class: 'chart__total', 'text-anchor': 'middle' });
    value.textContent = String(total);
    const label = svg('text', { x: 100, y: 120, class: 'chart__axis', 'text-anchor': 'middle' });
    label.textContent = 'enquiries';
    chart.append(value, label);

    figure.querySelector('.chart__svg')?.remove();
    figure.prepend(chart);
}

function drawTopProducts(figure, products) {
    figure.querySelector('.chart__svg')?.remove();

    if (products.length === 0) {
        return;
    }

    const rowHeight = 44;
    const width = 320;
    const max = Math.max(...products.map((product) => product.count));
    const chart = svg('svg', { viewBox: `0 0 ${width} ${products.length * rowHeight}`, class: 'chart__svg', role: 'img', 'aria-hidden': 'true' });

    products.forEach((product, index) => {
        const top = index * rowHeight;
        const name = svg('text', { x: 0, y: top + 14, class: 'chart__label' });
        name.textContent = product.name.length > 34 ? `${product.name.slice(0, 33)}…` : product.name;
        const count = svg('text', { x: width, y: top + 14, class: 'chart__label chart__label--strong', 'text-anchor': 'end' });
        count.textContent = String(product.count);
        chart.append(name, count);
        chart.append(svg('rect', { x: 0, y: top + 22, width, height: 8, rx: 4, class: 'chart__track-bar' }));
        chart.append(svg('rect', { x: 0, y: top + 22, width: Math.max(8, (product.count / max) * width), height: 8, rx: 4, class: 'chart__bar' }));
    });

    figure.prepend(chart);
}

function render(root, data) {
    root.querySelectorAll('[data-stat]').forEach((element) => {
        const value = data.totals[element.dataset.stat];

        if (value !== undefined) {
            element.textContent = String(value);
        }
    });

    data.statuses.forEach((status) => {
        const element = root.querySelector(`[data-status-count="${status.status}"]`);

        if (element) {
            element.textContent = String(status.count);
        }
    });

    const trend = root.querySelector('[data-chart="trend"]');
    const statuses = root.querySelector('[data-chart="statuses"]');
    const topProducts = root.querySelector('[data-chart="topProducts"]');
    const topEmpty = root.querySelector('[data-top-empty]');

    if (trend) {
        drawTrend(trend, data.trend);
    }

    if (statuses) {
        drawStatuses(statuses, data.statuses);
    }

    if (topProducts) {
        drawTopProducts(topProducts, data.topProducts);
    }

    if (topEmpty) {
        topEmpty.hidden = data.topProducts.length > 0;
    }
}

async function refresh(root) {
    try {
        const response = await fetch(root.dataset.statisticsUrl, {
            headers: { Accept: 'application/json' },
            credentials: 'same-origin',
        });

        if (response.ok) {
            const data = await response.json();
            render(root, data);
            return data;
        }
    } catch {
        // Network hiccup: keep the figures on screen and try again next time.
    }

    return null;
}

export function initDashboardCharts() {
    const root = document.querySelector('[data-dashboard]');
    const embedded = root?.querySelector('[data-dashboard-data]');

    if (!root || !embedded) {
        return;
    }

    let latest = JSON.parse(embedded.textContent);
    render(root, latest);

    const trend = root.querySelector('[data-chart="trend"]');
    let compact = trend ? isCompact(trend) : false;

    // Redraw the trend chart only when the phone/desktop drawing changes.
    if (trend && 'ResizeObserver' in window) {
        new ResizeObserver(() => {
            if (isCompact(trend) !== compact) {
                compact = isCompact(trend);
                drawTrend(trend, latest.trend);
            }
        }).observe(trend);
    }

    const update = async () => {
        if (document.visibilityState === 'visible') {
            latest = (await refresh(root)) ?? latest;
        }
    };

    window.setInterval(update, REFRESH_MS);
    document.addEventListener('visibilitychange', update);
}
