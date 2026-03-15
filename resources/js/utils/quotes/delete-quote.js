// /resources/js/utils/quotes/delete-quote.js

import { createDeleteHandler } from '../../factories/delete-factory.js';
import { showToast } from '../../ui/toast.js';
import { updateCount } from '../../components/table-pagination-count.js';

/**
 * Attaches delete functionality to the quotes table via delegation.
 */
export function initDeleteQuote(tableSelector = '#quotes-tbody') {
    const tbody = document.querySelector(tableSelector);
    if (!tbody) return;

    // Initialize the factory pointing to the quotes API
    const baseUrl = window.APP_CONFIG?.baseUrl || '/';
    const deleteHandler = createDeleteHandler(`${baseUrl}api/quotes`, 'Quote');

    // Delegate click to delete buttons (Red theme triggers)
    tbody.addEventListener('click', (e) => {
        const btn = e.target.closest('.delete-quote-btn');
        if (!btn) return;

        e.stopPropagation(); // Prevent row click navigation / View Modal trigger

        const row = btn.closest('tr[data-encoded-id]');
        const encodedId = row?.dataset.encodedId;

        if (!encodedId || !row) {
            console.error('Delete failed: Missing encoded ID or row element.');
            return;
        }

        // Trigger the factory's confirmation modal (should handle Red "Delete" button)
        deleteHandler.showConfirmation(encodedId, row, (success) => {
            if (!success) return;

            // 1. Show high-contrast toast (Project colors)
            showToast('Quote successfully deleted', 'success');

            // 2. Update the count in the footer (Quote context)
            updateCount('quote', tableSelector, '#quotes-count');

            // 3. Handle empty state if no rows are left
            const remainingRows = tbody.querySelectorAll('tr:not(.empty-state-row)').length;
            if (remainingRows === 0) {
                tbody.innerHTML = `
                    <tr class="empty-state-row">
                        <td colspan="100%" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400 font-sans">
                            <div class="flex flex-col items-center">
                                <svg class="h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="font-bold text-gray-400 italic">No quotes found</p>
                                <p class="text-sm">Click the "New Quote" button to get started.</p>
                            </div>
                        </td>
                    </tr>
                `;
            }
        });
    });
}