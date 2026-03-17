// /resources/js/modals/quotes-modal.js

import { Modal } from '../factories/modal-factory.js';
import { quoteForm } from '../forms/quote-form.js';
import { fetchRegions } from '../api/regions-api.js';
import { fetchCountries } from '../api/countries-api.js';
import { handleQuoteFormSubmission } from '../utils/quotes/form-submit.js';

let quoteModal = null;

/**
 * Initialize form features after the modal opens
 */
function initFormFeatures(formId, mode, modalInstance) {
    const form = document.getElementById(formId);
    if (!form) return;

    // Handle Submission with FormData support for PDF uploads
    handleQuoteFormSubmission(form, mode, modalInstance);
}

// --- Add Quote ---
async function openAddQuoteModal() {
    // Defaulting to Canada (1) and Ontario (1) for Roofing Quotes
    const defaultCountryId = 1; 
    const [countries, regions] = await Promise.all([
        fetchCountries(),
        fetchRegions(defaultCountryId)
    ]);

    if (quoteModal) quoteModal.destroy();

    quoteModal = new Modal({
        id: 'add-quote-modal',
        title: 'Generate New Roofing Quote',
        content: quoteForm({
            mode: 'add',
            formId: 'quotes-add-form',
            buttonLabel: 'Create Quote',
            countries,
            regions,
            countryId: defaultCountryId,
            regionId: 1 // Ontario
        }),
        size: 'md', // Reduced from lg for a tighter look
        showFooter: false,
    });

    quoteModal.open();
    initFormFeatures('quotes-add-form', 'add', quoteModal);
}

// --- Edit (Modify) Quote ---
export async function openEditQuoteModal(trigger) {
    const btn = trigger.closest('.edit-quote-btn') || trigger;
    if (!btn?.dataset) return;

    const data = btn.dataset;
    const countryId = parseInt(data.countryId || '1');

    const [countries, regions] = await Promise.all([
        fetchCountries(),
        fetchRegions(countryId)
    ]);

    if (quoteModal) quoteModal.destroy();

    quoteModal = new Modal({
        id: 'edit-quote-modal',
        title: `Modify Quote: ${data.quoteNumber || 'Estimate'}`,
        content: quoteForm({
            mode: 'edit',
            formId: 'quotes-edit-form',
            // Corrected mapping: data.address matches your data-row, 
            // and data.pdfFile matches 'pdf-file' from your PHP
            propertyAddress: data.address, 
            city: data.city,
            postalCode: data.postalCode,
            pdfFileName: data.pdfFile, // This captures 'data-pdf-file'
            countryId: countryId,
            regionId: parseInt(data.regionId || '1'),
            statusId: parseInt(data.statusId || '1'),
            countries,
            regions,
            buttonLabel: 'Update Quote',
            encodedId: data.encodedId
        }),
        size: 'md',
        showFooter: false,
    });

    quoteModal.open();
    initFormFeatures('quotes-edit-form', 'edit', quoteModal);
}

let listenersAttached = false;
export function initQuotesModal() {
    if (listenersAttached) return;

    document.addEventListener('click', (e) => {
        // Handle Add Button
        const addBtn = e.target.closest('#add-quote-btn');
        if (addBtn) {
            e.preventDefault();
            openAddQuoteModal();
            return;
        }

        // Handle Edit Button (from Table or View Modal)
        const editBtn = e.target.closest('.edit-quote-btn') || e.target.closest('#view-quote-edit-btn');
        
        if (editBtn) {
            e.preventDefault();
            e.stopPropagation(); 
            
            // Close the View Detail modal if it's open before showing Edit
            const viewModalElement = document.getElementById('view-quote-modal');
            if (viewModalElement && !viewModalElement.classList.contains('hidden')) {
                viewModalElement.classList.add('hidden'); 
            }

            openEditQuoteModal(editBtn);
        }
    });

    listenersAttached = true;
}