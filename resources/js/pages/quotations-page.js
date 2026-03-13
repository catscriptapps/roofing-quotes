// /resources/js/pages/quotations-page.js
import { AnimationEngine } from '../utils/animations';
import { loadPartial } from '../utils/spa-router.js';

export function init() {
    AnimationEngine.refresh();

    const requestBtn = document.querySelector('#request-quotation-btn');
    if (requestBtn) {
        requestBtn.addEventListener('click', (e) => {
            e.preventDefault();
            
            // 1. Set the hand-off flag
            sessionStorage.setItem('trigger_add_quote_modal', 'true');

            // 2. Navigate via SPA router
            const url = `${window.APP_CONFIG.baseUrl}my-quotations`;
            loadPartial(url);
            
            // Update title for history/tab
            document.title = `My Quotations | ${window.APP_CONFIG?.appName || 'Gonachi'}`;
        });
    }
}