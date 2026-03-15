// /resources/js/utils/quotes/form-submit.js

import { FormValidator } from '../../utils/form-validator.js';
import { buttonSpinner } from '../../utils/spinner-utils.js';
import { updateCount } from '../../components/table-pagination-count.js';

/**
 * Maps form data to an API payload for Roofing Quotes
 */
function getPayload(form) {
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    
    return {
        encoded_id: form.dataset.encodedId || null,
        property_address: data.address?.trim(),
        city: data.city?.trim(),
        postal_code: data.postalCode?.trim()?.toUpperCase(),
        access_code: data.accessCode?.trim(),
        country_id: parseInt(data.countryId || '1'),
        region_id: parseInt(data.regionId || '1'),
        status_id: parseInt(data.statusId || '1'), // 1 = Draft, 2 = Posted
    };
}

/**
 * Handles Add/Edit quote form submission
 */
export function handleQuoteFormSubmission(
    form, 
    mode, 
    modalInstance, 
    tableSelector = '#quotes-tbody'
) {
    if (form._quoteFormListenerAttached) return;
    form._quoteFormListenerAttached = true;

    const validator = new FormValidator(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    let apiMsg = form.querySelector('.api-message');

    // Create message container if it doesn't exist (Red/Black project style)
    if (!apiMsg) {
        apiMsg = document.createElement('div');
        apiMsg.className = 'api-message mt-4 transition-all duration-300';
        form.appendChild(apiMsg);
    }

    const originalLabel = submitBtn.innerHTML;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        // Basic validation for empty property address/city
        if (!validator.validateForEmptyFields(e)) return;

        submitBtn.disabled = true;
        submitBtn.innerHTML = buttonSpinner; 
        apiMsg.innerHTML = '';

        try {
            const payload = getPayload(form);
            
            // Method spoofing for PUT requests
            if (mode === 'edit') payload._method = 'PUT';

            const baseUrl = window.APP_CONFIG?.baseUrl || '/';
            const response = await fetch(`${baseUrl}api/quotes`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload),
            });

            const result = await response.json();

            if (result.success) {
                // 1. UPDATE TABLE
                const tbody = document.querySelector(tableSelector);
                if (tbody) {
                    if (mode === 'edit' && result.rowHtml) {
                        // Find by ID or the custom encoded-id attribute we use in the row
                        const existingRow = document.getElementById(`quote-row-${result.data?.id}`) || 
                                           document.querySelector(`tr[data-encoded-id="${payload.encoded_id}"]`);
                        
                        if (existingRow) {
                            existingRow.outerHTML = result.rowHtml;
                        }
                    } else if (result.rowHtml) {
                        // Insert new quote at the top
                        tbody.insertAdjacentHTML('afterbegin', result.rowHtml);
                    }
                    
                    // Update the "X quotes found" count in footer
                    updateCount('quote', tableSelector, '#quotes-count');
                }

                // Success Message (Brand Red/Green)
                apiMsg.innerHTML = `
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-2xl font-bold text-sm mt-2 flex items-center gap-2">
                        <i class="bi bi-check-circle-fill"></i>
                        ${result.messages?.[0] || 'Quote saved successfully.'}
                    </div>
                `;

                // Close modal after a brief success pause
                setTimeout(() => {
                    if (modalInstance && typeof modalInstance.close === 'function') {
                        modalInstance.close();
                    }
                }, 800);

            } else {
                // Error handling (Red theme)
                apiMsg.innerHTML = (result.messages || ['Error saving quote']).map(msg => `
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl font-bold text-sm mt-2 flex items-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        ${msg}
                    </div>
                `).join('');
            }

        } catch (err) {
            console.error('Quote Submission Error:', err);
            apiMsg.innerHTML = `
                <div class="bg-red-50 text-red-700 px-4 py-3 rounded-2xl font-bold text-sm mt-2">
                    An unexpected connection error occurred.
                </div>`;
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalLabel;
            }
        }
    });
}