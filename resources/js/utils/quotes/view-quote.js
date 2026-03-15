// /resources/js/utils/quotes/view-quote.js

/**
 * Initialize the detailed View Modal for Roofing Quotes.
 */
export function initViewQuote() {
    document.addEventListener('click', (e) => {
        const trigger = e.target.closest('.view-quote-trigger');
        if (!trigger) return;

        const data = trigger.dataset;
        const modal = document.getElementById('view-quote-modal');
        if (!modal) return;

        // 1. Header Information (Quote Number & Status)
        const quoteNumEl = document.getElementById('view-quote-number');
        const statusEl = document.getElementById('view-quote-status');
        const addressSubEl = document.getElementById('view-quote-address-sub');
        
        if (quoteNumEl) quoteNumEl.textContent = data.quoteNumber || 'Q26-XXXX';
        if (addressSubEl) addressSubEl.textContent = data.address || 'Unknown Property';

        if (statusEl) {
            const statusLabel = data.statusLabel || 'Draft';
            statusEl.textContent = statusLabel;
            
            // Apply Red/Black styling based on status
            if (statusLabel.toLowerCase() === 'posted') {
                statusEl.className = 'px-3 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest border border-gray-900 bg-gray-900 text-white dark:border-gray-700';
            } else {
                statusEl.className = 'px-3 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest border border-red-100 bg-red-50 text-red-600';
            }
        }

        // 2. Property & Access Details
        const fullAddressEl = document.getElementById('view-quote-full-address');
        const postalCodeEl = document.getElementById('view-quote-postal-code');
        const accessCodeEl = document.getElementById('view-quote-access-code');

        if (fullAddressEl) fullAddressEl.textContent = data.address || '---';
        if (postalCodeEl) postalCodeEl.textContent = data.postalCode || '---';
        if (accessCodeEl) accessCodeEl.textContent = data.accessCode || 'NONE';

        // 3. Jurisdictional Info
        const countryEl = document.getElementById('view-quote-country');
        const regionEl = document.getElementById('view-quote-region');
        const cityEl = document.getElementById('view-quote-city');

        if (countryEl) countryEl.textContent = data.countryName || 'Canada';
        if (regionEl) regionEl.textContent = data.regionName || 'Ontario';
        if (cityEl) cityEl.textContent = data.city || '---';

        // 4. PDF Link Logic
        const pdfLinkEl = document.getElementById('view-quote-pdf-link');
        if (pdfLinkEl) {
            if (data.pdfFileName && data.pdfFileName !== 'null') {
                pdfLinkEl.href = `${window.APP_CONFIG?.baseUrl}storage/quotes/${data.pdfFileName}`;
                pdfLinkEl.textContent = data.pdfFileName;
                pdfLinkEl.classList.add('text-red-400');
            } else {
                pdfLinkEl.href = '#';
                pdfLinkEl.textContent = 'No Document Attached';
                pdfLinkEl.classList.remove('text-red-400');
            }
        }

        // 5. Log History
        const createdEl = document.getElementById('view-quote-created');
        const updatedEl = document.getElementById('view-quote-updated');

        if (createdEl) createdEl.textContent = data.createdAt || '---';
        if (updatedEl) updatedEl.textContent = data.updatedAt || '---';

        // 6. Owner/Generator Info (Using your modal-detail-owner component logic)
        const ownerNameEl = document.getElementById('quote-owner-name');
        const ownerEmailEl = document.getElementById('quote-owner-email');
        if (ownerNameEl) ownerNameEl.textContent = data.ownerName || 'System Generated';
        if (ownerEmailEl) ownerEmailEl.textContent = data.ownerEmail || '';

        // 7. Edit Button logic (Redirects to Edit Modal)
        const editBtn = document.getElementById('view-quote-edit-btn');
        if (editBtn) {
            // Transfer dataset to the edit button so the edit modal can read it
            Object.assign(editBtn.dataset, data);
        }

        modal.classList.remove('hidden');
    });

    // Close Modals (Overlay/Dismiss Button)
    document.addEventListener('click', (e) => {
        const isCloseTrigger = e.target.closest('.close-view-quote-modal') || e.target.id === 'close-view-quote-modal-overlay';
        if (isCloseTrigger) {
            const modal = document.getElementById('view-quote-modal');
            if (modal) modal.classList.add('hidden');
        }
    });

    // Close Modal (Escape key)
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const modal = document.getElementById('view-quote-modal');
            if (modal && !modal.classList.contains('hidden')) {
                modal.classList.add('hidden');
            }
        }
    });
}