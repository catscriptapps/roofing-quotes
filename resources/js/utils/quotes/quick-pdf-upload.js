// /resources/js/utils/quotes/quick-pdf-upload.js

import { showToast } from '../../ui/toast.js';
import { showSpinner, hideSpinner } from '../../ui/spinner.js';

/**
 * Handles fast PDF uploads directly from the table row.
 */
export function initQuickPdfUpload() {
    const tableBody = document.getElementById('quotes-tbody');
    if (!tableBody) return;

    // Create a single hidden file input to reuse
    let fileInput = document.getElementById('quick-pdf-input');
    if (!fileInput) {
        fileInput = document.createElement('input');
        fileInput.type = 'file';
        fileInput.id = 'quick-pdf-input';
        fileInput.accept = '.pdf';
        fileInput.className = 'hidden';
        document.body.appendChild(fileInput);
    }

    let activeRow = null;
    let activeBtn = null;

    tableBody.addEventListener('click', (e) => {
        const btn = e.target.closest('.quick-pdf-btn');
        if (!btn) return;

        e.stopPropagation();
        activeBtn = btn;
        activeRow = btn.closest('tr[data-encoded-id]');
        
        // Reset and trigger the hidden input
        fileInput.value = '';
        fileInput.click();
    });

    fileInput.addEventListener('change', async () => {
        if (!fileInput.files.length || !activeRow) return;

        const file = fileInput.files[0];
        const encodedId = activeRow.dataset.encodedId;
        const originalContent = activeBtn.innerHTML;

        // Validation
        if (file.type !== 'application/pdf') {
            showToast('Please select a valid PDF file.', 'error');
            return;
        }

        // 1. UI Feedback: Show global spinner & turn small button into local spinner
        showSpinner(); 
        activeBtn.disabled = true;
        activeBtn.innerHTML = '<span class="animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full"></span>';

        const formData = new FormData();
        formData.append('pdf', file);
        formData.append('encoded_id', encodedId);
        formData.append('_method', 'PATCH'); 

        try {
            const baseUrl = window.APP_CONFIG?.baseUrl || '/';
            const response = await fetch(`${baseUrl}api/quotes/upload-pdf`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (result.success) {
                showToast('Document uploaded successfully', 'success');
                
                if (result.rowHtml) {
                    activeRow.outerHTML = result.rowHtml;
                } else {
                    activeRow.dataset.pdfFileName = file.name;
                    const linkEl = activeRow.querySelector('.pdf-link-placeholder');
                    if (linkEl) linkEl.textContent = file.name;
                }
            } else {
                showToast(result.messages?.[0] || 'Upload failed', 'error');
                activeBtn.innerHTML = originalContent;
            }
        } catch (error) {
            console.error('Quick Upload Error:', error);
            showToast('Connection error during upload', 'error');
            activeBtn.innerHTML = originalContent;
        } finally {
            // 2. Hide global spinner and re-enable button
            hideSpinner();
            if (activeBtn) {
                activeBtn.disabled = false;
            }
        }
    });
}