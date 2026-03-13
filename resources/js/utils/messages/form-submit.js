// /resources/js/utils/messages/form-submit.js

import { FormValidator } from '../../utils/form-validator.js';
import { buttonSpinner } from '../../utils/spinner-utils.js';
import { updateCount } from '../../components/table-pagination-count.js';

/**
 * Maps form data to an API payload for Messages
 */
function getPayload(form) {
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    
    return {
        action: 'create', // Explicitly telling the controller to create
        full_name: 'System User', // Or fetch current user name
        email: data.email?.trim(),
        subject: data.subject?.trim(),
        message: data.message?.trim(),
        is_sent: true,   // Since this is being sent from the dashboard
        is_read: true    // Sent messages are usually considered "read" by the sender
    };
}

/**
 * Handles Compose/Reply message form submission
 */
export function handleMessageFormSubmission(
    form, 
    mode, 
    modalInstance, 
    tableSelector = '#messages-tbody'
) {
    if (form._messageFormListenerAttached) return;
    form._messageFormListenerAttached = true;

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

        // UI State: Loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = buttonSpinner; 
        apiMsg.innerHTML = '';

        try {
            const payload = getPayload(form);
            const baseUrl = window.APP_CONFIG?.baseUrl || '/';
            
            const response = await fetch(`${baseUrl}api/messages`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload),
            });

            const result = await response.json();

            if (result.success) {
                // 1. --- TABLE UPDATE LOGIC ---
                // Only update the table if the user is currently looking at the 'Sent' folder
                const currentFolder = new URLSearchParams(window.location.search).get('folder') || 'inbox';
                const tbody = document.querySelector(tableSelector);
                
                if (tbody && currentFolder === 'sent' && result.rowHtml) {
                    // Remove "No messages found" row
                    const emptyStateRow = tbody.querySelector('.empty-state-row') || 
                                         tbody.querySelector('td[colspan]')?.closest('tr');
                    if (emptyStateRow) emptyStateRow.remove();

                    // Insert the new message at the top
                    tbody.insertAdjacentHTML('afterbegin', result.rowHtml);

                    // Update the "Showing X messages" count
                    updateCount('message', tableSelector, '#messages-count');
                }

                // 2. --- SUCCESS FEEDBACK ---
                apiMsg.innerHTML = `
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded-xl font-bold text-sm mt-2 text-center">
                        ${result.message || 'Message sent successfully!'}
                    </div>
                `;

                // 3. --- CLEANUP & CLOSE ---
                submitBtn.style.display = 'none'; 
                
                setTimeout(() => {
                    if (modalInstance && typeof modalInstance.close === 'function') {
                        modalInstance.close();
                    }
                }, 800);

            } else {
                // Error UI
                apiMsg.innerHTML = `
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded-xl font-bold text-sm mt-2 text-center">
                        ${result.message || 'Check your input'}
                    </div>`;
            }

        } catch (err) {
            console.error('Submission Error:', err);
            apiMsg.innerHTML = `
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded-xl font-bold text-sm mt-2 text-center">
                    Unexpected error. Please try again.
                </div>`;
        } finally {
            if (submitBtn.style.display !== 'none') {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalLabel;
            }
        }
    });
}