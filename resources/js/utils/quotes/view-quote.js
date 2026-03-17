// /resources/js/utils/quotes/view-quote.js
import { modalDetailOwner } from "../../ui/modal-detail-owner";
import { copyToClipboard } from "../globals/copy-to-clipboard";

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

        // 1. Header Information
        const quoteNumEl = document.getElementById('view-quote-number');
        const statusEl = document.getElementById('view-quote-status');
        const addressSubEl = document.getElementById('view-quote-address-sub');
        
        if (quoteNumEl) quoteNumEl.textContent = data.quoteNumber || 'Unknown';
        if (addressSubEl) addressSubEl.textContent = data.address || 'Unknown Property';

        if (statusEl) {
            const statusLabel = data.statusLabel || 'Draft';
            statusEl.textContent = statusLabel;
            statusEl.className = statusLabel.toLowerCase() === 'posted' 
                ? 'px-3 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest border border-green-900 bg-green-900 text-white'
                : 'px-3 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest border border-red-100 bg-red-50 text-red-600';
        }

        // Initial Circle
        const initialEl = document.getElementById('view-quote-initial');
        if (initialEl) {
            const address = data.address || '';
            
            // Remove leading numbers and spaces (e.g., "123 Test St" -> "Test St")
            const addressNoNumber = address.replace(/^[0-9\s]+/, '');
            
            // Grab the first character of what remains, or '#' if empty
            const initial = addressNoNumber.charAt(0).toUpperCase() || '#';
            
            initialEl.textContent = initial;
        }

        // 2. Property & Access Details
        const fullAddressEl = document.getElementById('view-quote-full-address');
        const postalCodeEl = document.getElementById('view-quote-postal-code');
        const accessCodeEl = document.getElementById('view-quote-access-code');

        if (fullAddressEl) fullAddressEl.textContent = data.address  + ' - ' + data.city + ', ' + data.regionName + ' ';
        if (postalCodeEl) postalCodeEl.textContent = data.postalCode || '---';
        
        if (accessCodeEl) {
            accessCodeEl.textContent = data.accessCode || '----';
            accessCodeEl.setAttribute('data-code', data.accessCode || '');
            accessCodeEl.classList.add('copy-to-clipboard', 'cursor-pointer');
            copyToClipboard();
        }

        // 3. Owner detail logic
        modalDetailOwner('quote', data);

        // 4. PDF Proxy Logic (Roofing Quotes)
        const pdfLinkEl = document.getElementById('view-quote-pdf-link');
        const pdfIconContainer = document.getElementById('view-quote-pdf-icon');

        if (pdfLinkEl) {
            // Note: We use the encodedId from trigger.dataset, which matches your table row
            const encodedId = data.encodedId; 

            if (encodedId && data.pdfFile && data.pdfFile !== 'null') {
                const base = window.APP_CONFIG?.baseUrl?.replace(/\/+$/, '') ?? '';
                
                // Pointing to our secure proxy instead of a public path
                pdfLinkEl.href = `${base}/api/quotes-pdf?id=${encodeURIComponent(encodedId)}`;
                pdfLinkEl.target = "_blank"; // Ensure it opens in a new tab
                pdfLinkEl.textContent = data.pdfFile;
                pdfLinkEl.classList.add('text-red-600', 'hover:underline');
                
                if (pdfIconContainer) {
                    pdfIconContainer.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-red-600">
                            <path d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0016.5 9h-1.875a1.875 1.875 0 01-1.875-1.875V5.25A3.75 3.75 0 009 1.5H5.625z" />
                            <path d="M12.971 1.816A5.23 5.23 0 0114.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 013.434 1.279 9.768 9.768 0 00-6.963-6.963z" />
                        </svg>`;
                }
            } else {
                pdfLinkEl.href = '#';
                pdfLinkEl.textContent = 'No Document Attached';
                pdfLinkEl.classList.remove('text-red-600');
                if (pdfIconContainer) {
                    pdfIconContainer.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-gray-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>`;
                }
            }
        }

        // 5. Log History
        if (document.getElementById('view-quote-created')) document.getElementById('view-quote-created').textContent = data.createdAt || '---';
        if (document.getElementById('view-quote-expires')) document.getElementById('view-quote-expires').textContent = data.expiresAt || '---';
        if (document.getElementById('view-quote-updated')) document.getElementById('view-quote-updated').textContent = data.updatedAt || '---';

        // 6. Inspector Info (Targets your modal-detail-owner.php IDs)
        const nameEl = document.getElementById('quote-owner-name');
        const badgeContainer = document.getElementById('quote-owner-badges');
        const avatarImg = document.getElementById('quote-owner-avatar-img');
        const avatarPlaceholder = document.getElementById('quote-owner-avatar-placeholder');

        if (nameEl) nameEl.textContent = data.ownerName || 'System Generated';
        
        // Avatar Logic
        if (data.ownerAvatar && data.ownerAvatar !== '') {
            if (avatarImg) { avatarImg.src = data.ownerAvatar; avatarImg.classList.remove('hidden'); }
            if (avatarPlaceholder) avatarPlaceholder.classList.add('hidden');
        } else {
            if (avatarImg) avatarImg.classList.add('hidden');
            if (avatarPlaceholder) {
                avatarPlaceholder.classList.remove('hidden');
                avatarPlaceholder.textContent = (data.ownerName || 'U').charAt(0).toUpperCase();
            }
        }

        // Badges Logic
        if (badgeContainer) {
            try {
                const types = JSON.parse(data.ownerTypes || '[]');
                badgeContainer.innerHTML = types.map(type => `
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                        ${type}
                    </span>
                `).join('') || '<span class="text-[10px] text-gray-400 italic">Inspector</span>';
            } catch (e) {
                badgeContainer.innerHTML = '<span class="text-[10px] text-gray-400 italic">Inspector</span>';
            }
        }

        modal.classList.remove('hidden');
    });

    // Close Modals
    document.addEventListener('click', (e) => {
        const isCloseTrigger = e.target.closest('.close-view-quote-modal') || e.target.id === 'close-view-quote-modal-overlay';
        if (isCloseTrigger) {
            const modal = document.getElementById('view-quote-modal');
            if (modal) modal.classList.add('hidden');
        }
    });

    // Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const modal = document.getElementById('view-quote-modal');
            if (modal && !modal.classList.contains('hidden')) {
                modal.classList.add('hidden');
            }
        }
    });
}