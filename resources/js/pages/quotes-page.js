// /resources/js/pages/quotes-page.js

import { initQuotesModal } from '../modals/quotes-modal.js';
import { updateCount } from '../components/table-pagination-count.js';
import { initDeleteQuote } from '../utils/quotes/delete-quote.js';
import { enableTableSearch } from '../components/table-search.js';
import { initViewQuote } from '../utils/quotes/view-quote.js';
import { initQuoteInfiniteScroll } from '../utils/quotes/infinite-scroll-quotes.js';
import { copyToClipboard } from '../utils/globals/copy-to-clipboard.js';
import { initPDFDownload } from '../utils/quotes/pdf-download.js'; // 1. Import the new logic

/**
 * Initialize the Completed Estimates page logic.
 */
export function init() {
    initQuotesModal();
    
    enableTableSearch({
        searchInputId: 'quotes-search',
        tbodyId: 'quotes-tbody',
        countId: 'quotes-count',
        endpoint: `${window.APP_CONFIG?.baseUrl}api/quotes`,
        resourceLabel: 'quote',
        addButtonId: 'add-quote-btn'
    });

    initDeleteQuote();

    // 2. Initialize the PDF View triggers (The small red circles)
    initPDFDownload();

    updateCount('quote', '#quotes-tbody', '#quotes-count');
    
    initViewQuote();

    initQuoteInfiniteScroll();

    copyToClipboard();
}