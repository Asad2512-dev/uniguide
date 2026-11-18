export function shouldTrackNavigation(event, href, current, { target = '', download = false, ignore = false } = {}) {
    if (event.defaultPrevented || event.button > 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey || ignore || download || (target && target !== '_self')) return false;
    try {
        const next = new URL(href, current);
        const here = new URL(current);
        return ['http:', 'https:'].includes(next.protocol) && next.origin === here.origin
            && !(next.pathname === here.pathname && next.search === here.search && next.hash);
    } catch { return false; }
}

export function submissionLabel({ method = 'get', upload = false, action = '' } = {}) {
    if (method.toLowerCase() === 'get') return 'Loading results…';
    if (upload) return 'Uploading files…';
    if (/\/login(?:\?|$)/.test(action)) return 'Signing in…';
    if (/\/logout(?:\?|$)/.test(action)) return 'Signing out…';
    if (/\/register(?:\?|$)/.test(action)) return 'Creating your account…';
    return 'Saving changes…';
}

// Track overlapping work without fabricated percentage estimates or artificial waits.
export function createProgressTracker(onChange, { delay = 120, setTimer = setTimeout, clearTimer = clearTimeout } = {}) {
    const active = new Map();
    let timer = null;
    let visible = false;
    const notify = () => onChange({ visible, label: [...active.values()].at(-1) || '', count: active.size });
    return {
        start(label = 'Loading…') {
            const token = Symbol();
            active.set(token, label);
            if (visible) notify();
            else if (timer === null) timer = setTimer(() => { timer = null; visible = active.size > 0; notify(); }, delay);
            let finished = false;
            return () => {
                if (finished) return;
                finished = true;
                active.delete(token);
                if (!active.size) {
                    if (timer !== null) clearTimer(timer);
                    timer = null; visible = false;
                }
                notify();
            };
        },
        reset() {
            if (timer !== null) clearTimer(timer);
            timer = null; visible = false; active.clear(); notify();
        },
    };
}

export function installProgress(window, document) {
    const strip = document.querySelector('[data-page-progress]');
    const status = document.querySelector('[data-progress-status]');
    const label = document.querySelector('[data-progress-message]');
    const pending = new Map();
    const tracker = createProgressTracker((state) => {
        if (strip) strip.hidden = !state.visible;
        if (status) status.hidden = !state.visible;
        if (label) label.textContent = state.label;
    });
    const beginElement = (element, message, submitter = element) => {
        const release = tracker.start(message);
        const previousBusy = element.getAttribute('aria-busy');
        const previousDisabled = submitter?.getAttribute('aria-disabled');
        element.setAttribute('aria-busy', 'true');
        element.dataset.progressPending = '';
        submitter?.setAttribute('aria-disabled', 'true');
        // Keep controls enabled: disabling a submitter drops its name/value from native form data.
        const spinner = document.createElement('span');
        spinner.className = 'progress-spinner'; spinner.setAttribute('aria-hidden', 'true');
        const spinnerTimer = window.setTimeout(() => submitter?.appendChild(spinner), 120);
        const cleanup = () => {
            window.clearTimeout(spinnerTimer); spinner.remove(); release();
            if (previousBusy === null) element.removeAttribute('aria-busy'); else element.setAttribute('aria-busy', previousBusy);
            if (previousDisabled === null) submitter?.removeAttribute('aria-disabled'); else submitter?.setAttribute('aria-disabled', previousDisabled);
            delete element.dataset.progressPending; pending.delete(element);
        };
        pending.set(element, cleanup);
        return cleanup;
    };
    const reset = () => { [...pending.values()].forEach((finish) => finish()); tracker.reset(); };
    window.UniGuideProgress = { start: tracker.start, reset };
    window.addEventListener('pageshow', reset);
    window.addEventListener('pagehide', reset);
    window.addEventListener('beforeunload', () => tracker.start('Loading page…'));

    document.addEventListener('submit', (event) => {
        const form = event.target;
        if (!(form instanceof window.HTMLFormElement) || event.defaultPrevented || form.closest('[data-progress-ignore]') || form.method === 'dialog' || (form.target && form.target !== '_self')) return;
        if (pending.has(form)) { event.preventDefault(); return; }
        const upload = [...form.querySelectorAll('input[type="file"]')].some((input) => input.files?.length);
        beginElement(form, submissionLabel({ method: form.method, action: form.action, upload }), event.submitter || form.querySelector('button[type="submit"], button:not([type])'));
    });

    const download = async (anchor) => {
        const finish = beginElement(anchor, 'Preparing your download…');
        try {
            const response = await window.fetch(anchor.href, { credentials: 'same-origin', headers: { Accept: 'application/octet-stream' } });
            if (!response.ok || response.headers.get('content-type')?.includes('text/html')) throw new Error('Your download could not be completed. Check your session and try again.');
            const blob = await response.blob();
            const objectUrl = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = objectUrl;
            const disposition = response.headers.get('content-disposition') || '';
            const fileName = disposition.match(/filename="([^"]+)"/i)?.[1] || anchor.download || 'uniguide-download';
            link.download = fileName.split(/[\\/]/).at(-1);
            document.body.appendChild(link); link.click(); link.remove();
            window.setTimeout(() => window.URL.revokeObjectURL(objectUrl), 60000);
        } catch (error) {
            window.Alpine?.store('toasts')?.add({ title: 'Download unavailable', message: error.message, type: 'error' });
        } finally { finish(); }
    };
    document.addEventListener('click', (event) => {
        const anchor = event.target.closest?.('a[href]');
        if (!anchor || anchor.closest('[data-progress-ignore]')) return;
        if (pending.has(anchor)) { event.preventDefault(); return; }
        if (anchor.hasAttribute('data-download') && shouldTrackNavigation(event, anchor.href, window.location.href, { target: anchor.target })) {
            event.preventDefault(); void download(anchor); return;
        }
        if (shouldTrackNavigation(event, anchor.href, window.location.href, { target: anchor.target, download: anchor.hasAttribute('download') })) beginElement(anchor, 'Loading page…');
    });
    return { reset, tracker };
}
