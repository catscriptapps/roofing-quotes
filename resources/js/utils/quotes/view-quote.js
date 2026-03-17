// /resources/js/utils/quotes/view-quote.js
import { modalDetailOwner } from "../../ui/modal-detail-owner";

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
        if (accessCodeEl) accessCodeEl.textContent = data.accessCode || '----';

        // 3. Owner detail logic
        modalDetailOwner('quote', data);

        // 4. PDF Link Logic (Using Heroicons)
        const pdfLinkEl = document.getElementById('view-quote-pdf-link');
        const pdfIconContainer = document.getElementById('view-quote-pdf-icon');
        
        if (pdfLinkEl) {
            if (data.pdfFile && data.pdfFile !== 'null') {
                pdfLinkEl.href = `${window.APP_CONFIG?.baseUrl || '/'}public/pdfs/quotes/${data.pdfFile}`;
                pdfLinkEl.textContent = data.pdfFile;
                pdfLinkEl.classList.add('text-red-400');
                if (pdfIconContainer) {
                    pdfIconContainer.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-red-500"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>`;
                }
            } else {
                pdfLinkEl.href = 'javascript';
                pdfLinkEl.textContent = 'No Document Attached';
                pdfLinkEl.classList.remove('text-red-400');
                if (pdfIconContainer) {
                    pdfIconContainer.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-gray-500"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>`;
                }
            }
        }

        // 5. Log History
        if (document.getElementById('view-quote-created')) document.getElementById('view-quote-created').textContent = data.createdAt || '---';
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