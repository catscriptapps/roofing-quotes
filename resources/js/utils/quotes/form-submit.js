// /resources/js/utils/quotes/form-submit.js

import { FormValidator } from '../../utils/form-validator.js';
import { showSpinner, hideSpinner } from '../../ui/spinner.js';
import { updateCount } from '../../components/table-pagination-count.js';

/**
 * Maps form data to a FormData object for Roofing Quotes (Supports Files)
 */
function getPayload(form) {
    const formData = new FormData(form);
    
    // Add the encoded ID if editing
    if (form.dataset.encodedId) {
        formData.append('encoded_id', form.dataset.encodedId);
    }
    
    // Ensure the postal code is uppercase in the payload
    if (formData.has('postal_code')) {
        formData.set('postal_code', formData.get('postal_code').toUpperCase());
    }

    return formData;
}

/**
 * Handles Add/Edit quote form submission (Multipart for PDF support)
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

    if (!apiMsg) {
        apiMsg = document.createElement('div');
        apiMsg.className = 'api-message mt-4 transition-all duration-300';
        form.appendChild(apiMsg);
    }

    const originalLabel = submitBtn.innerHTML;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        if (!validator.validateForEmptyFields(e)) return;

        // UI Feedback: Show global overlay and disable button
        showSpinner(); 
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full mr-2"></span> Saving...';
        apiMsg.innerHTML = '';

        try {
            const formData = getPayload(form);
            
            // Method spoofing for PUT (Laravel/Eloquent often prefers POST with _method PATCH/PUT for files)
            if (mode === 'edit') {
                formData.append('_method', 'PUT');
            }

            const baseUrl = window.APP_CONFIG?.baseUrl || '/';
            
            // IMPORTANT: No 'Content-Type' header here. 
            // The browser will automatically set 'multipart/form-data' with the correct boundary.
            const response = await fetch(`${baseUrl}api/quotes`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (result.success) {
                const tbody = document.querySelector(tableSelector);
                if (tbody) {
                    if (mode === 'edit' && result.rowHtml) {
                        const existingRow = document.querySelector(`tr[data-encoded-id="${form.dataset.encodedId}"]`);
                        if (existingRow) existingRow.outerHTML = result.rowHtml;
                    } else if (result.rowHtml) {
                        // Remove empty state if it exists before adding first row
                        const emptyRow = tbody.querySelector('.empty-state-row');
                        if (emptyRow) emptyRow.remove();
                        
                        tbody.insertAdjacentHTML('afterbegin', result.rowHtml);
                    }
                    updateCount('quote', tableSelector, '#quotes-count');
                }

                apiMsg.innerHTML = `
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-2xl font-bold text-sm mt-2 flex items-center gap-2">
                        <i class="bi bi-check-circle-fill"></i>
                        ${result.messages?.[0] || 'Quote saved successfully.'}
                    </div>
                `;

                setTimeout(() => {
                    if (modalInstance && typeof modalInstance.close === 'function') {
                        modalInstance.close();
                    }
                }, 800);

            } else {
                apiMsg.innerHTML = (result.messages || ['Error saving quote']).map(msg => `
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl font-bold text-sm mt-2 flex items-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        ${msg}
                    </div>
                `).join('');
            }

        } catch (err) {
            console.error('Quote Submission Error:', err);
            apiMsg.innerHTML = `<div class="bg-red-50 text-red-700 px-4 py-3 rounded-2xl font-bold text-sm mt-2">Connection error.</div>`;
        } finally {
            hideSpinner(); // Hide global overlay
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalLabel;
            }
        }
    });
}