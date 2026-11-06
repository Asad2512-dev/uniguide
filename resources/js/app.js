import Alpine from 'alpinejs';
import counselorWorkspace from './counselor-workspace.js';
Alpine.data('counselorWorkspace', counselorWorkspace);
import { workspaceHeight, validateStep } from './mobile-layout.js';
// Global Toast Store in Alpine
document.addEventListener('alpine:init', () => {
    Alpine.store('toasts', {
        items: [],
        counter: 0,
        add(options, maybeType = 'info', maybeDuration = 4500) {
            let title = '';
            let message = '';
            let type = maybeType;
            let duration = maybeDuration;

            if (typeof options === 'string') {
                message = options;
            } else if (typeof options === 'object' && options !== null) {
                title = options.title || '';
                message = options.message || '';
                type = options.type || maybeType;
                duration = options.duration !== undefined ? options.duration : 4500;
            }

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

// Keep dialog focus and background interaction scoped to the active overlay.
Alpine.directive('modal-focus', (element, { expression }, { evaluateLater, effect, cleanup }) => {
    const evaluate = evaluateLater(expression);
    let release = null;
    effect(() => evaluate((visible) => {
        if (!visible) {
            release?.();
            release = null;
            return;
        }
        if (release) return;
        const previous = document.activeElement;
        const previousOverflow = document.body.style.overflow;
        const background = [];
        let branch = element;
        while (branch.parentElement && branch !== document.body) {
            for (const sibling of branch.parentElement.children) {
                if (sibling !== branch && sibling instanceof HTMLElement) {
                    background.push([sibling, sibling.inert]);
                    sibling.inert = true;
                }
            }
            branch = branch.parentElement;
        }
        const scrollAncestors = [];
        for (let ancestor = element.parentElement; ancestor && ancestor !== document.body; ancestor = ancestor.parentElement) {
            if (/(auto|scroll)/.test(getComputedStyle(ancestor).overflowY)) {
                scrollAncestors.push([ancestor, ancestor.style.overflow]);
                ancestor.style.overflow = 'hidden';
            }
        }
        document.body.style.overflow = 'hidden';
        const focusable = () => [...element.querySelectorAll('a[href], button, input, select, textarea, [tabindex="0"]')]
            .filter((item) => !item.disabled && item.getClientRects().length && !item.closest('[inert]'));
        const trap = (event) => {
            if (event.key !== 'Tab') return;
            const items = focusable();
            const first = items[0];
            const last = items.at(-1);
            if (!first) { event.preventDefault(); return; }
            if (event.shiftKey && (document.activeElement === first || !element.contains(document.activeElement))) {
                event.preventDefault(); last.focus();
            } else if (!event.shiftKey && (document.activeElement === last || !element.contains(document.activeElement))) {
                event.preventDefault(); first.focus();
            }
        };
        element.addEventListener('keydown', trap);
        let active = true;
        requestAnimationFrame(() => { if (active) focusable()[0]?.focus(); });
        release = () => {
            active = false;
            element.removeEventListener('keydown', trap);
            background.forEach(([sibling, inert]) => { sibling.inert = inert; });
            document.body.style.overflow = previousOverflow;
            scrollAncestors.forEach(([ancestor, overflow]) => { ancestor.style.overflow = overflow; });
            if (previous?.isConnected) previous.focus({ preventScroll: true });
        };
    }));
    cleanup(() => release?.());
});

// Size app workspaces to the visible viewport, including the software keyboard.
Alpine.directive('viewport-workspace', (element, bindings, { cleanup }) => {
    const viewport = window.visualViewport;
    let frame;
    const update = () => {
        cancelAnimationFrame(frame);
        frame = requestAnimationFrame(() => {
            const height = viewport?.height ?? window.innerHeight;
            const top = Math.max(0, element.getBoundingClientRect().top - (viewport?.offsetTop ?? 0));
            element.style.height = `${workspaceHeight(height, top)}px`;
            const editing = document.activeElement?.matches('input, textarea, select, [contenteditable="true"]');
            document.documentElement.toggleAttribute('data-keyboard-open', Boolean(editing && window.innerHeight - height > 150));
        });
    };
    viewport?.addEventListener('resize', update);
    viewport?.addEventListener('scroll', update);
    window.addEventListener('resize', update);
    update();
    cleanup(() => {
        cancelAnimationFrame(frame);
        viewport?.removeEventListener('resize', update);
        viewport?.removeEventListener('scroll', update);
        window.removeEventListener('resize', update);
    });
});

window.validateUniGuideStep = validateStep;

// Expose Alpine
window.Alpine = Alpine;
Alpine.start();

// Charts are requested only by screens that render them; callers do not consume a synchronous result.
window.createUniGuideChart = async (...args) => {
    try {
        const { createUniGuideChart } = await import('./charts.js');
        return createUniGuideChart(...args);
    } catch (error) {
        console.error('Unable to initialize UniGuide chart:', error);
        return null;
    }
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

// Use one set of management controls at every width; label the existing cells.
let mobileCellId = 0;
for (const table of document.querySelectorAll('table[data-mobile-table]')) {
    const labelRows = () => {
        const headers = [...table.querySelectorAll('thead th')].map((header) => header.textContent.replace(/\s+/g, ' ').trim());
        if (!headers.length) return;
        table.classList.add('mobile-management-table');
        table.setAttribute('role', 'table');
        for (const row of table.querySelectorAll('tbody tr')) {
            row.setAttribute('role', 'row');
            const cells = [...row.children].filter((cell) => cell.matches('td, th'));
            row.toggleAttribute('data-summary-row', cells.some((cell) => cell.colSpan > 1));
            cells.forEach((cell, index) => {
                cell.dataset.label = headers[index] || 'Actions';
                cell.setAttribute('role', 'cell');
                cell.toggleAttribute('data-mobile-essential', /actions?|status|verification|review|selection/i.test(cell.dataset.label));
            });
            if (cells.length > 4 && !row.hasAttribute('data-summary-row') && !row.querySelector('[data-row-toggle]')) {
                const toggle = document.createElement('button');
                toggle.type = 'button';
                toggle.dataset.rowToggle = '';
                toggle.className = 'mobile-row-toggle mt-2 rounded-lg border border-border-default px-3 text-xs font-semibold text-brand-700';
                toggle.textContent = 'Show details';
                toggle.setAttribute('aria-expanded', 'false');
                const controlled = cells.slice(3).filter((cell) => !cell.hasAttribute('data-mobile-essential'));
                if (!controlled.length) continue;
                controlled.forEach((cell) => { cell.id ||= `mobile-cell-${++mobileCellId}`; });
                toggle.setAttribute('aria-controls', controlled.map((cell) => cell.id).join(' '));
                toggle.addEventListener('click', () => {
                    const expanded = row.toggleAttribute('data-expanded');
                    toggle.setAttribute('aria-expanded', String(expanded));
                    toggle.textContent = expanded ? 'Fewer details' : 'Show details';
                });
                cells[0].appendChild(toggle);
            }
        }
    };
    labelRows();
    new MutationObserver(labelRows).observe(table, { childList: true, subtree: true });
}
