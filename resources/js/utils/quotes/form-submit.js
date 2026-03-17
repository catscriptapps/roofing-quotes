// /resources/js/utils/quotes/form-submit.js

import { FormValidator } from '../../utils/form-validator.js';
import { showSpinner, hideSpinner } from '../../ui/spinner.js';
import { updateCount } from '../../components/table-pagination-count.js';
import { showToast } from '../../ui/toast.js';

/**
 * Maps form data to a FormData object for Roofing Quotes
 */
function getPayload(form, mode) {
    const formData = new FormData(form);
    
    if (form.dataset.encodedId) {
        formData.append('encoded_id', form.dataset.encodedId);
    }
    
    if (formData.has('postal_code')) {
        formData.set('postal_code', formData.get('postal_code').toUpperCase());
    }

    // --- Intelligence Logic ---
    // If we are editing and no new file was selected, 
    // remove the pdf_file key so the server knows not to overwrite
    const fileInput = form.querySelector('input[type="file"][name="pdf_file"]');
    if (mode === 'edit' && (!fileInput || fileInput.files.length === 0)) {
        formData.delete('pdf_file');
    }

    return formData;
}

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
    const fileInput = form.querySelector('input[type="file"][name="pdf_file"]');

    // Instant Feedback for PDF type
    fileInput?.addEventListener('change', () => {
        const file = fileInput.files[0];
        if (file && file.type !== 'application/pdf') {
            showToast('Only PDF files are allowed.', 'error');
            fileInput.value = ''; 
        }
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        if (!validator.validateForEmptyFields(e)) return;

        // Strict PDF Requirement for "Add" mode only
        if (mode === 'add' && (!fileInput || fileInput.files.length === 0)) {
            apiMsg.innerHTML = `
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl font-bold text-sm mt-2 flex items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    Please select a PDF quote document to continue.
                </div>
            `;
            return; 
        }

        showSpinner(); 
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="animate-spin inline-block w-4 h-4 border-current border-t-transparent rounded-full mr-2"></span> Saving...';
        apiMsg.innerHTML = '';

        try {
            // Pass 'mode' to getPayload for intelligence check
            const formData = getPayload(form, mode);
            
            if (mode === 'edit') {
                formData.append('_method', 'PUT');
            }

            const baseUrl = window.APP_CONFIG?.baseUrl || '/';
            
            const response = await fetch(`${baseUrl}api/quotes`, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const result = await response.json();

            if (result.success) {
                const tbody = document.querySelector(tableSelector);
                if (tbody && result.rowHtml) {
                    if (mode === 'edit') {
                        const existingRow = document.querySelector(`tr[data-encoded-id="${form.dataset.encodedId}"]`);
                        if (existingRow) existingRow.outerHTML = result.rowHtml;
                    } else {
                        // --- IMPROVED CLEANUP ---
                        // Look for the specific class, OR any row that spans all columns (typical for empty states)
                        const emptyRow = tbody.querySelector('.empty-state-row') || tbody.querySelector('td[colspan]');
                        
                        if (emptyRow) {
                            // If it's the <td>, we need to remove its parent <tr>
                            const rowToRemove = emptyRow.tagName === 'TR' ? emptyRow : emptyRow.closest('tr');
                            if (rowToRemove) rowToRemove.remove();
                        }

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

                submitBtn.style.display = "none";

                setTimeout(() => {
                    if (modalInstance?.close) modalInstance.close();
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
            hideSpinner(); 
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalLabel;
            }
        }
    });
}