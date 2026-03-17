// /resources/js/pages/quotes-page.js

import { initQuotesModal } from '../modals/quotes-modal.js';
import { updateCount } from '../components/table-pagination-count.js';
import { initDeleteQuote } from '../utils/quotes/delete-quote.js';
import { enableTableSearch } from '../components/table-search.js';
import { initViewQuote } from '../utils/quotes/view-quote.js';
import { initQuoteInfiniteScroll } from '../utils/quotes/infinite-scroll-quotes.js';
import { initQuickPdfUpload } from '../utils/quotes/quick-pdf-upload.js';
import { copyToClipboard } from '../utils/globals/copy-to-clipboard.js';

/**
 * Initialize the Roofing Quotes page logic.
 */
export function init() {
    // 1. Initialize the Create/Edit modal logic (Modify Quote)
    initQuotesModal();
    
    // 2. Enable the pro AJAX search - Red focus handled via CSS
    enableTableSearch({
        searchInputId: 'quotes-search',
        tbodyId: 'quotes-tbody',
        countId: 'quotes-count',
        endpoint: `${window.APP_CONFIG?.baseUrl}api/quotes`,
        resourceLabel: 'quote',
        addButtonId: 'add-quote-btn'
    });

    // 3. Initialize the delete/archive functionality
    initDeleteQuote();

    // 4. Initial count check for the footer
    updateCount('quote', '#quotes-tbody', '#quotes-count');
    
    // 5. Initialize the detailed View Modal (The one with Red/Black branding)
    initViewQuote();

    // 6. Initialize Infinite Scroll for performance
    initQuoteInfiniteScroll();

    // 7. Initialize the Quick PDF trigger (the small red button on the table row)
    initQuickPdfUpload();

    copyToClipboard();
}