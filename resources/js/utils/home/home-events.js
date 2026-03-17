// /resources/js/utils/home/home-events.js

import { AnimationEngine } from "../animations.js";
import { showToast } from "../../ui/toast";

export function initHomeEvents() {
    // Fire the AOS refresh to catch the new content
    AnimationEngine.refresh();

    // Fire the quote lookup event
    initQuoteLookup();
}

/**
 * Initialize the Home page client quote PDF lookup event.
 */
function initQuoteLookup() {
    const input = document.getElementById('access-code');
    const submitBtn = document.getElementById('access-code-submit-btn');
    const validationMsg = document.querySelector('#quote-pdf-lookup-form .validation-msg');

    if (!input || !submitBtn || !validationMsg) return;

    validationMsg.style.display = 'none';

    // Auto-uppercase logic as the user types
    input.addEventListener('input', () => {
        // Force uppercase
        input.value = input.value.toUpperCase();
        
        // Reset validation styles
        validationMsg.style.display = 'none';
        input.classList.remove('border-red-500');
    });

    submitBtn.addEventListener('click', async (e) => {
        e.preventDefault();

        const value = input.value.trim();
        if (!value) {
            validationMsg.style.display = 'block';
            input.classList.add('border-red-500', 'animate-shake');
            setTimeout(() => input.classList.remove('animate-shake'), 500);
            input.focus();
            return;
        }

        submitBtn.disabled = true;
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Loading...';

        try {
            const res = await fetch(`${window.APP_CONFIG.baseUrl}api/quote-lookup`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ access_code: value })
            });

            const contentType = res.headers.get('Content-Type') || '';

            // ✅ SCENARIO 3: PDF returned
            if (contentType.includes('application/pdf')) {
                const blob = await res.blob();
                const url = window.URL.createObjectURL(blob);
                showToast('Quote PDF successfully retrieved', 'success');

                // Open PDF in new tab
                window.open(url, '_blank');
                return;
            }

            // Otherwise it's JSON
            const data = await res.json();

            // ❌ SCENARIO 1: invalid code
            if (data.status === 'invalid') {
                showToast('Access code invalid', 'error');
                return;
            }

            // ❌ SCENARIO 2: expired code
            if (data.status === 'expired') {
                showToast('Access code has expired', 'error');
                return;
            }

            showToast(data.message || 'Unexpected response', 'error');

        } catch (err) {
            console.error(err);
            showToast('Failed to load quote PDF', 'error');
        } finally {
            // Reset Button State
            submitBtn.disabled = false;
            submitBtn.textContent = 'Get Report';

            // Wipe and Refocus (Regardless of success/fail)
            input.value = '';
            input.focus();
        }
    });
}
