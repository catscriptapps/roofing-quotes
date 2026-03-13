// /resources/js/utils/adverts/view-advert.js

import { ViewContentMapper } from './view-content-mapper.js';
import { viewMedia } from './view-media.js';
import { modalDetailOwner } from "../../ui/modal-detail-owner.js";

export function initViewAdvert() {
    // 1. Singleton Check
    if (window._viewAdListenerAttached) return;

    // 2. ATTACH EXTERNAL LISTENERS (The "Shouts")
    ViewContentMapper.initMediaListeners();

    // 3. MAIN TRIGGER: OPEN VIEW MODAL
    document.addEventListener('click', (e) => {
        const trigger = e.target.closest('.view-ad-trigger');
        if (!trigger || e.target.closest('.edit-ad-btn, .delete-ad-btn, .dropdown')) return;

        const data = trigger.dataset;
        const modal = document.getElementById('view-ad-modal');
        if (!modal) return;

        // Map everything through the Mapper
        ViewContentMapper.mapAll(data);
        
        // Owner Detail Logic
        modalDetailOwner('ad', data);
        
        // Prep ID and fetch initial pictures
        const adId = data.id || data.encodedId;
        modal.dataset.adId = adId; 
        viewMedia(adId);

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; 
    });

    // 4. ATTACH UI BEHAVIORS (Close buttons, Upload triggers)
    ViewContentMapper.initUIBehaviors();

    window._viewAdListenerAttached = true;
}