// /resources/js/utils/quotations/view-quotation.js

import { ViewContentMapper } from './view-content-mapper.js';
import { viewMedia } from './view-media.js';
import { modalDetailOwner } from "../../ui/modal-detail-owner.js";

export function initViewQuotation() {
    if (window._viewQuoteListenerAttached) return;

    // 1. ATTACH EXTERNAL LISTENERS (The "Shouts")
    ViewContentMapper.initMediaListeners();

    // 2. MAIN TRIGGER: OPEN VIEW MODAL
    document.addEventListener('click', (e) => {
        const trigger = e.target.closest('.view-quote-trigger');
        if (!trigger || e.target.closest('.edit-quote-btn, .delete-quote-btn, .dropdown')) return;

        const data = trigger.dataset;
        const modal = document.getElementById('view-quote-modal');
        if (!modal) return;

        // Map everything through the Mapper
        ViewContentMapper.mapAll(data);
        
        // Owner Detail Logic
        modalDetailOwner('quote', data);
        
        // Prep ID and fetch initial pictures
        const quoteId = data.id || data.encodedId;
        modal.dataset.quoteId = quoteId; 
        viewMedia(quoteId);

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; 
    });

    // 3. ATTACH UI BEHAVIORS (Close buttons, Upload triggers)
    ViewContentMapper.initUIBehaviors();

    window._viewQuoteListenerAttached = true;
}