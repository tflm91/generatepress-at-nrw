let lastFocusedElement = null;

function closeDialogue() {
    document.getElementById("delete-dialogue").style.display = "none";
    if (lastFocusedElement) {
        lastFocusedElement.focus();
    }
}

function trapFocus(modal) {
    const focusableSelectors = 'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"]';
    const focusableElements = modal.querySelectorAll(focusableSelectors);
    const first = focusableElements[0];
    const last = focusableElements[focusableElements.length - 1];

    if (first) first.focus();

    modal.addEventListener('keydown', (e) => {
        if (e.key === 'Tab') {
            if (e.shiftKey) {
                if (document.activeElement === first) {
                    e.preventDefault();
                    last.focus();
                }
            } else {
                if (document.activeElement === last) {
                    e.preventDefault();
                    first.focus();
                }
            }
        }
        if (e.key === 'Escape') {
            closeDialogue();
        }
    });
}

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("h1, h2, h3, h4, h5, h6").forEach(heading => {
        heading.setAttribute("tabindex", 0);
    });
})
