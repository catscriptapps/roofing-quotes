// /resources/js/utils/users/form-submit.js

import { FormValidator } from '../../utils/form-validator.js';
import { buttonSpinner } from '../../utils/spinner-utils.js';
import { updateCount } from '../../components/table-pagination-count.js';
import { initProfileModal } from '../../modals/profile-modal.js';

/**
 * Maps form data to an API payload for Users
 */
function getPayload(form) {
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    
    // Capture multiple User Types (Roles)
    const userTypeIds = formData.getAll('userTypeIds[]');
    
    return {
        encoded_id: form.dataset.encodedId || null,
        first_name: data.firstName?.trim(),
        last_name: data.lastName?.trim(),
        email: data.email?.trim(),
        password: data.password || null,
        address: data.address?.trim(),
        city: data.city?.trim(),
        avatar_url: data.avatarUrl?.trim(),
        country_id: parseInt(data.countryId),
        region_id: parseInt(data.regionId),
        user_type_ids: userTypeIds.map(id => parseInt(id)),
        status_id: form.querySelector('input[name="isActive"]')?.checked ? 1 : 0,
    };
}

/**
 * Handles Add/Edit user form submission
 */
export function handleUserFormSubmission(
    form, 
    mode, 
    modalInstance, 
    tableSelector = '#users-tbody'
) {
    if (form._userFormListenerAttached) return;
    form._userFormListenerAttached = true;

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

        submitBtn.disabled = true;
        submitBtn.innerHTML = buttonSpinner; 
        apiMsg.innerHTML = '';

        try {
            const payload = getPayload(form);
            // Use POST with _method spoofing for Laravel/Eloquent compatibility on PUT requests
            if (mode === 'edit') payload._method = 'PUT';

            const baseUrl = window.APP_CONFIG?.baseUrl || '/';
            const response = await fetch(`${baseUrl}api/users`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload),
            });

            const result = await response.json();

            if (result.success) {
                // 1. UPDATE TABLE (Users List Page)
                const tbody = document.querySelector(tableSelector);
                if (tbody) {
                    if (mode === 'edit' && result.rowHtml) {
                        const existingRow = document.getElementById(`user-row-${result.data?.id}`) || 
                                           document.querySelector(`tr[data-encoded-id="${payload.encoded_id}"]`);
                        if (existingRow) existingRow.outerHTML = result.rowHtml;
                    } else if (result.rowHtml) {
                        tbody.insertAdjacentHTML('afterbegin', result.rowHtml);
                    }
                    updateCount('user', tableSelector, '#users-count');
                }

                // 2. REFRESH PROFILE PAGE (If editing self)
                const profileWrapper = document.getElementById('partial-profile');
                if (profileWrapper && window.loadPartial) {
                    const currentUrl = window.location.pathname;
                    window.loadPartial(currentUrl, false).then(() => {
                        setTimeout(() => initProfileModal(), 100);
                    });
                }

                apiMsg.innerHTML = `
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded-xl font-bold text-sm mt-2">
                        ${result.messages?.[0] || 'Saved successfully.'}
                    </div>
                `;

                setTimeout(() => {
                    if (modalInstance && typeof modalInstance.close === 'function') {
                        modalInstance.close();
                    }
                }, 800);

            } else {
                apiMsg.innerHTML = (result.messages || ['Error']).map(msg => `
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded-xl font-bold text-sm mt-2">${msg}</div>
                `).join('');
            }

        } catch (err) {
            console.error('Submission Error:', err);
            apiMsg.innerHTML = `<div class="bg-red-100 text-red-700 px-4 py-2 rounded-xl font-bold text-sm mt-2">Unexpected error.</div>`;
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalLabel;
            }
        }
    });
}