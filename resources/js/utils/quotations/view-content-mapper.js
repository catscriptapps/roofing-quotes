// /resources/js/utils/quotations/view-content-mapper.js

import { openMediaUpload } from '../media-manager.js';
import { viewMedia } from './view-media.js';
import { closeTriggerESC } from '../helpers.js';

/**
 * Maps Quotation Data to the View Modal DOM - Gonachi Style 💎
 */
export const ViewContentMapper = {
    // A quick helper to call all mapping functions at once
    mapAll(data) {
        this.mapBasic(data);
        this.mapLocation(data);
        this.mapClassification(data);
        this.mapFinancials(data);
        this.mapLinks(data);
        this.mapStatus(data);
        this.mapMetadata(data);
        this.syncEditButton(data);
    },

    /**
     * Handles the "Shouts" from the Media Manager
     */
    initMediaListeners() {
        document.addEventListener('quotation:pics-updated', (e) => {
            const modal = document.getElementById('view-quote-modal');
            if (modal && modal.dataset.quoteId == e.detail.id) {
                viewMedia(e.detail.id);
            }
        });
    },

    /**
     * Handles Upload Button and Close triggers
     */
    initUIBehaviors() {
        // --- Upload Trigger ---
        document.addEventListener('click', (e) => {
            const uploadBtn = e.target.closest('#trigger-quote-pic-upload');
            if (!uploadBtn) return;

            e.preventDefault();
            const modal = document.getElementById('view-quote-modal');
            const quoteId = modal?.dataset.quoteId;
            if (!quoteId) return;

            openMediaUpload({
                type: 'quotation', 
                id: quoteId,
                gridId: '#quote-pics-wrapper'
            });
        });

        // --- Close Trigger (Click) ---
        document.addEventListener('click', (e) => {
            const isCloseTrigger = e.target.closest('.close-view-quote-modal') || e.target.id === 'close-view-quote-modal-overlay';
            if (isCloseTrigger) this.closeModal();
        });

        // --- Close Trigger (ESC) ---
        closeTriggerESC(this);
    },

    closeModal() {
        const modal = document.getElementById('view-quote-modal');
        if (modal && !modal.classList.contains('hidden')) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    },

    mapBasic(data) {
        const titleEl = document.getElementById('view-quote-title');
        const tradeSubEl = document.getElementById('view-quote-trade-sub');
        const initialEl = document.getElementById('view-quote-initial');
        const descEl = document.getElementById('view-quote-description');

        if (titleEl) titleEl.textContent = data.title || 'Untitled Project';
        if (tradeSubEl) tradeSubEl.textContent = data.skilledTradeName || 'General Labor';
        if (descEl) descEl.textContent = data.description || 'No detailed description provided.';

        if (initialEl) {
            const firstLetter = data.title ? data.title.trim().charAt(0).toUpperCase() : 'Q';
            initialEl.textContent = firstLetter;
        }
    },

    mapLocation(data) {
        const countryEl = document.getElementById('view-quote-country');
        const regionEl = document.getElementById('view-quote-region');
        const cityEl = document.getElementById('view-quote-city');

        if (countryEl) countryEl.textContent = data.countryName || '---';
        if (regionEl) regionEl.textContent = data.regionName || '---';
        if (cityEl) cityEl.textContent = data.city || '---';
    },

    mapClassification(data) {
        const contractorEl = document.getElementById('view-quote-contractor-type');
        const tradeEl = document.getElementById('view-quote-skilled-trade');
        const unitEl = document.getElementById('view-quote-unit-type');
        const houseEl = document.getElementById('view-quote-house-type');
        const houseWrapper = document.getElementById('view-quote-house-type-wrapper');

        if (contractorEl) contractorEl.textContent = data.contractorTypeName || '---';
        if (tradeEl) tradeEl.textContent = data.skilledTradeName || '---';
        if (unitEl) unitEl.textContent = data.unitTypeName || '---';
        
        if (houseEl) {
            houseEl.textContent = data.houseTypeName || 'N/A';
            if (data.unitTypeId == '5') {
                houseWrapper?.classList.remove('hidden');
            } else {
                houseWrapper?.classList.add('hidden');
            }
        }
    },

    mapFinancials(data) {
        const timeStartEl = document.getElementById('view-quote-timeline-start');
        const timeFinishEl = document.getElementById('view-quote-timeline-finish');
        const typeLabelEl = document.getElementById('view-quote-type-label');
        const budgetEl = document.getElementById('view-quote-budget');

        if (timeStartEl) timeStartEl.textContent = `${data.startDate || '--'} @ ${data.startTime || '--'}`;
        if (timeFinishEl) timeFinishEl.textContent = `${data.finishDate || '--'} @ ${data.finishTime || '--'}`;
        if (typeLabelEl) typeLabelEl.textContent = data.quotationTypeName || 'Standard';
        if (budgetEl) budgetEl.textContent = data.budget || 'Not Disclosed';
    },

    mapLinks(data) {
        const phoneEl = document.getElementById('view-quote-phone');
        const youtubeEl = document.getElementById('view-quote-url');
        const youtubeIconWrapper = youtubeEl?.closest('.flex')?.querySelector('.bg-white\\/10');

        if (phoneEl) phoneEl.textContent = (data.youtubeUrl && data.youtubeUrl !== '#') ? data.contactPhone : 'No phone provided';

        if (youtubeEl) {
            if (data.youtubeUrl && data.youtubeUrl !== '#' && data.youtubeUrl.trim() !== '') {
                youtubeEl.href = data.youtubeUrl;
                youtubeEl.textContent = 'Watch Project Video';
                youtubeEl.classList.remove('pointer-events-none', 'opacity-50', 'cursor-default');
                youtubeEl.classList.add('hover:text-primary-300');
                if (youtubeIconWrapper) youtubeIconWrapper.classList.remove('opacity-40', 'grayscale');
            } else {
                youtubeEl.removeAttribute('href');
                youtubeEl.textContent = 'No video provided';
                youtubeEl.classList.add('pointer-events-none', 'opacity-50', 'cursor-default');
                youtubeEl.classList.remove('hover:text-primary-300');
                if (youtubeIconWrapper) youtubeIconWrapper.classList.add('opacity-40', 'grayscale');
            }
        }
    },

    mapStatus(data) {
        const statusEl = document.getElementById('view-quote-status');
        if (!statusEl) return;

        const statusId = parseInt(data.statusId) || 1;
        let statusText = 'ACTIVE';
        let statusClasses = 'px-3 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest border ';

        switch (statusId) {
            case 2: statusText = 'AWARDED'; statusClasses += 'bg-green-50 text-green-600 border-green-100 dark:bg-green-900/20 dark:text-green-400 dark:border-green-800/30'; break;
            case 3: statusText = 'CLOSED'; statusClasses += 'bg-gray-100 text-gray-600 border-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700'; break;
            default: statusText = 'ACTIVE'; statusClasses += 'bg-primary-50 text-primary-400 border-primary-100 dark:bg-primary-900/20 dark:text-primary-300 dark:border-primary-800/30'; break;
        }
        statusEl.textContent = statusText;
        statusEl.className = statusClasses;
    },

    mapMetadata(data) {
        const createdEl = document.getElementById('view-quote-created');
        const updatedEl = document.getElementById('view-quote-updated');
        if (createdEl) createdEl.textContent = data.createdAt || '---';
        if (updatedEl) updatedEl.textContent = data.updatedAt || '---';
    },

    syncEditButton(data) {
        const viewEditBtn = document.getElementById('view-quote-edit-btn');
        if (viewEditBtn) {
            viewEditBtn.dataset.triggerOrigin = 'view-modal';
            Object.assign(viewEditBtn.dataset, data);
        }
    }
};