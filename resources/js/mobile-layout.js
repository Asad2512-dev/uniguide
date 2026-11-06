/** Keep the workspace within the visible viewport after browser chrome or keyboard changes. */
export function workspaceHeight(viewportHeight, top) {
    return Math.max(0, viewportHeight - Math.max(0, top));
}

/** Validate the current visible wizard step before moving its controls off screen. */
export function validateStep(root, step) {
    const panel = root.querySelector(`[data-step="${step}"]`);
    const invalid = panel && [...panel.querySelectorAll('input, select, textarea')]
        .find((control) => !control.disabled && !control.checkValidity());
    if (invalid) {
        invalid.reportValidity();
        return false;
    }
    return true;
}
