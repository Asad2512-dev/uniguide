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

// Chart defaults shared by the analytics and design-system screens.
export function createUniGuideChart(canvasOrId, userConfig) {
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
}
