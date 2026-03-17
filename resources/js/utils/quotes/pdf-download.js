// /resources/js/utils/quotes/pdf-download.js

/**
 * Attaches the functionality for viewing a quote PDF.
 * Uses event delegation to handle clicks on individual row triggers.
 */
export function initPDFDownload() {
    const tbody = document.getElementById('quotes-tbody');

    if (!tbody) return;

    // Use event delegation to catch clicks on any .view-pdf-trigger
    tbody.addEventListener('click', (e) => {
        // Find the closest trigger button
        const trigger = e.target.closest('.view-pdf-trigger');
        
        if (!trigger) return;

        e.preventDefault();

        // Retrieve the ID from the data attribute we added to the trigger
        const encodedId = trigger.dataset.encodedId;

        if (!encodedId) {
            console.error('Quote ID missing from trigger.');
            return;
        }

        const base = window.APP_CONFIG?.baseUrl?.replace(/\/+$/, '') ?? '';
        
        // Pointing to your new retrofitted API endpoint
        const streamUrl = `${base}/api/quotes-pdf?id=${encodeURIComponent(encodedId)}`;
        
        // Open in a new tab for viewing
        window.open(streamUrl, '_blank', 'noopener,noreferrer');
    });
}