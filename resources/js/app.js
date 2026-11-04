import Alpine from 'alpinejs';
import {
    Chart,
    LineController,
    BarController,
    DoughnutController,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    BarElement,
    ArcElement,
    Title,
    Tooltip,
    Legend,
    Filler,
} from 'chart.js';

// Register Chart.js components
Chart.register(
    LineController,
    BarController,
    DoughnutController,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    BarElement,
    ArcElement,
    Title,
    Tooltip,
    Legend,
    Filler
);

// Global Toast Store in Alpine
document.addEventListener('alpine:init', () => {
    Alpine.store('toasts', {
        items: [],
        counter: 0,
        add({ title = '', message = '', type = 'info', duration = 4500 }) {
            const id = ++this.counter;
            const item = { id, title, message, type, visible: true };
            this.items.push(item);

            if (duration > 0) {
                setTimeout(() => {
                    this.remove(id);
                }, duration);
            }
            return id;
        },
        remove(id) {
            const index = this.items.findIndex((item) => item.id === id);
            if (index !== -1) {
                this.items[index].visible = false;
                setTimeout(() => {
                    this.items = this.items.filter((item) => item.id !== id);
                }, 200);
            }
        },
    });

    // Global Command Palette Store
    Alpine.store('commandPalette', {
        open: false,
        toggle() {
            this.open = !this.open;
        },
        close() {
            this.open = false;
        },
    });
});

// Expose Alpine
window.Alpine = Alpine;
Alpine.start();

// Expose Chart.js Helper with UniGuide Brand Defaults
window.createUniGuideChart = function (canvasOrId, userConfig) {
    const el = typeof canvasOrId === 'string' ? document.getElementById(canvasOrId) : canvasOrId;
    if (!el) return null;

    const baseConfig = {
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        font: {
                            family: 'Inter, sans-serif',
                            size: 12,
                            weight: 500,
                        },
                        color: '#4B5565',
                        usePointStyle: true,
                        boxWidth: 8,
                    },
                },
                tooltip: {
                    backgroundColor: '#111827',
                    titleFont: { family: 'Plus Jakarta Sans, sans-serif', size: 13, weight: 600 },
                    bodyFont: { family: 'Inter, sans-serif', size: 12 },
                    padding: 10,
                    cornerRadius: 8,
                    boxShadow: '0 10px 15px -3px rgba(0, 0, 0, 0.2)',
                },
            },
            scales: {
                x: {
                    grid: { color: '#F1F4F9' },
                    ticks: { color: '#667085', font: { family: 'Inter, sans-serif', size: 11 } },
                },
                y: {
                    grid: { color: '#F1F4F9' },
                    ticks: { color: '#667085', font: { family: 'Inter, sans-serif', size: 11 } },
                },
            },
        },
    };

    // Deep merge options
    const merged = {
        ...userConfig,
        options: {
            ...baseConfig.options,
            ...(userConfig.options || {}),
            plugins: {
                ...baseConfig.options.plugins,
                ...((userConfig.options && userConfig.options.plugins) || {}),
            },
        },
    };

    return new Chart(el, merged);
};

// Global Hotkeys (Cmd+K / Ctrl+K)
window.addEventListener('keydown', (e) => {
    if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
        e.preventDefault();
        const store = window.Alpine?.store('commandPalette');
        if (store) {
            store.toggle();
        }
    }
});
