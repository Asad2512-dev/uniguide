import test from 'node:test';
import assert from 'node:assert/strict';
import { shouldTrackNavigation, submissionLabel, createProgressTracker } from '../../resources/js/progress.js';

const current = 'https://uniguide.test/student/dashboard';
const click = { button: 0, defaultPrevented: false };

test('navigation progress only tracks actual same-tab page requests', () => {
    assert.equal(shouldTrackNavigation(click, '/student/profile', current), true);
    assert.equal(shouldTrackNavigation(click, '/student/dashboard?q=updated', current), true);
    for (const href of ['#section', '/student/dashboard#section', 'mailto:office@example.com', 'https://other.test/', 'javascript:void(0)']) {
        assert.equal(shouldTrackNavigation(click, href, current), false);
    }
    for (const extra of [{ ctrlKey: true }, { metaKey: true }, { shiftKey: true }, { altKey: true }, { button: 1 }, { defaultPrevented: true }]) {
        assert.equal(shouldTrackNavigation({ ...click, ...extra }, '/student/profile', current), false);
    }
    for (const options of [{ target: '_blank' }, { download: true }, { ignore: true }]) {
        assert.equal(shouldTrackNavigation(click, '/student/profile', current, options), false);
    }
});

test('forms use clear action-specific labels without claiming completion', () => {
    assert.equal(submissionLabel(), 'Loading results…');
    assert.equal(submissionLabel({ method: 'POST', upload: true }), 'Uploading files…');
    assert.equal(submissionLabel({ method: 'POST', action: 'https://uniguide.test/login' }), 'Signing in…');
    assert.equal(submissionLabel({ method: 'POST', action: '/register' }), 'Creating your account…');
    assert.equal(submissionLabel({ method: 'POST', action: '/student/profile' }), 'Saving changes…');
});

function harness() {
    const timers = new Map(); const states = []; let id = 0;
    const tracker = createProgressTracker((state) => states.push(state), {
        setTimer: (fn) => { timers.set(++id, fn); return id; },
        clearTimer: (key) => timers.delete(key),
    });
    return { tracker, states, timers, flush() { for (const fn of [...timers.values()]) fn(); timers.clear(); } };
}

test('fast requests finish without flashing a loader', () => {
    const h = harness(); const finish = h.tracker.start('Saving…');
    finish(); assert.equal(h.timers.size, 0);
    assert.deepEqual(h.states.at(-1), { visible: false, count: 0, label: '' });
});

test('overlapping work keeps the loader visible until every operation finishes', () => {
    const h = harness(); const first = h.tracker.start('First request');
    h.flush(); const second = h.tracker.start('Second request');
    assert.deepEqual(h.states.at(-1), { visible: true, count: 2, label: 'Second request' });
    second(); assert.deepEqual(h.states.at(-1), { visible: true, count: 1, label: 'First request' });
    first(); first(); assert.deepEqual(h.states.at(-1), { visible: false, count: 0, label: '' });
});

test('restoring a page resets work and stale completions cannot resurrect the loader', () => {
    const h = harness(); const finish = h.tracker.start('Pending');
    h.flush(); h.tracker.reset(); finish();
    assert.deepEqual(h.states.at(-1), { visible: false, count: 0, label: '' });
    assert.equal(h.timers.size, 0);
});

test('native form loading prevents duplicates without dropping the submitter decision value', async () => {
    const { installProgress } = await import('../../resources/js/progress.js');
    class Element {
        constructor() { this.attributes = new Map(); this.dataset = {}; this.children = []; this.hidden = true; }
        getAttribute(key) { return this.attributes.get(key) ?? null; }
        setAttribute(key, value) { this.attributes.set(key, value); }
        removeAttribute(key) { this.attributes.delete(key); }
        closest() { return null; }
        appendChild(node) { this.children.push(node); }
        remove() {}
    }
    class Form extends Element {
        method = 'post'; action = '/admin/platform/applications/1/review'; target = '';
        querySelectorAll() { return []; }
        querySelector() { return null; }
    }
    const handlers = {}; const windowHandlers = {};
    const window = { HTMLFormElement: Form, setTimeout, clearTimeout, addEventListener(name, fn) { windowHandlers[name] = fn; } };
    const document = { querySelector() { return new Element(); }, createElement() { return new Element(); }, addEventListener(name, fn) { handlers[name] = fn; } };
    const instance = installProgress(window, document);
    const form = new Form(); const button = new Element();
    button.name = 'decision'; button.value = 'approved'; button.disabled = false;
    const first = { target: form, submitter: button, defaultPrevented: false };
    handlers.submit(first);
    assert.equal(form.getAttribute('aria-busy'), 'true');
    assert.equal(button.disabled, false);
    assert.equal(button.name, 'decision'); assert.equal(button.value, 'approved');
    let prevented = false;
    handlers.submit({ ...first, preventDefault() { prevented = true; } });
    assert.equal(prevented, true);
    windowHandlers.pageshow();
    assert.equal(form.getAttribute('aria-busy'), null);
    assert.equal(button.getAttribute('aria-disabled'), null);
    const alpineForm = new Form();
    handlers.submit({ target: alpineForm, defaultPrevented: true });
    assert.equal(alpineForm.getAttribute('aria-busy'), null);
    instance.reset();
});
