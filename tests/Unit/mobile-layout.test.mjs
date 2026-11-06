import { test } from 'node:test';
import assert from 'node:assert/strict';
import { workspaceHeight } from '../../resources/js/mobile-layout.js';

test('chat fits below the mobile header', () => {
    assert.equal(workspaceHeight(844, 64), 780);
});
test('workspace height decreases with the visible keyboard area', () => {
    assert.equal(workspaceHeight(390, 64), 326);
});
test('landscape and panned viewports never create a negative workspace', () => {
    assert.equal(workspaceHeight(320, -20), 320);
    assert.equal(workspaceHeight(50, 64), 0);
});

const { validateStep } = await import('../../resources/js/mobile-layout.js');
test('a wizard cannot advance with an invalid field hidden on its next step', () => {
    let announced = false;
    const invalid = { disabled: false, checkValidity: () => false, reportValidity: () => { announced = true; } };
    const root = { querySelector: () => ({ querySelectorAll: () => [invalid] }) };
    assert.equal(validateStep(root, 2), false);
    assert.equal(announced, true);
});
test('a valid wizard step can advance', () => {
    const root = { querySelector: () => ({ querySelectorAll: () => [{ disabled: false, checkValidity: () => true }] }) };
    assert.equal(validateStep(root, 2), true);
});
