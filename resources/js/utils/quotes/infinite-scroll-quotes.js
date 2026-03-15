// /resources/js/utils/quotes/infinite-scroll-quotes.js

/**
 * Initialize Infinite Scroll for the Quotes table.
 */
export function initQuoteInfiniteScroll() {
    const tableBody = document.getElementById('quotes-tbody');
    if (!tableBody) return;

    let currentPage = 1;
    let isLoading = false;
    let hasMore = true;
    let throttleTimeout = null;

    const loadMoreQuotes = async () => {
        if (isLoading || !hasMore) return;

        isLoading = true;
        currentPage++;

        const searchInput = document.getElementById('quotes-search');
        const query = searchInput ? searchInput.value : '';
        
        try {
            // Fetch from the quotes API with pagination and search query
            const response = await fetch(`${window.APP_CONFIG?.baseUrl}api/quotes?page=${currentPage}&q=${encodeURIComponent(query)}`);
            const result = await response.json();

            if (result.success && result.data && result.data.length > 0) {
                // Map the results to row HTML strings (assuming the API returns pre-rendered rowHtml)
                const rowsHtml = result.data.map(item => item.rowHtml).join('');
                
                // Append the rows smoothly to the bottom of the table
                tableBody.insertAdjacentHTML('beforeend', rowsHtml);
                
                // Update the count label in the footer (e.g., "Showing 50 of 1200 quotes")
                const countEl = document.getElementById('quotes-count');
                if (countEl && result.meta) {
                    const currentCount = tableBody.querySelectorAll('tr:not(.empty-state-row)').length;
                    countEl.textContent = `Showing ${currentCount} of ${result.meta.total} quotes`;
                }

                hasMore = result.meta.hasMore;
            } else {
                hasMore = false;
            }
        } catch (error) {
            console.error("Infinite scroll error for quotes:", error);
            currentPage--; // Reset page index so the next scroll attempt can retry
        } finally {
            isLoading = false;
        }
    };

    const handleScroll = () => {
        if (throttleTimeout) return;

        throttleTimeout = setTimeout(() => {
            throttleTimeout = null;
            
            // Check if user is near the bottom (400px threshold)
            const scrollBottom = window.innerHeight + window.scrollY;
            const threshold = document.documentElement.scrollHeight - 400;

            if (scrollBottom >= threshold) {
                loadMoreQuotes();
            }
        }, 200); // 200ms Throttle to prevent scroll jank
    };

    window.addEventListener('scroll', handleScroll);
}