// /resources/js/utils/quotes/infinite-scroll-quotes.js

/**
 * Initialize Infinite Scroll for the Quotes table.
 */
export function initQuoteInfiniteScroll() {
    const tableBody = document.getElementById('quotes-tbody');
    if (!tableBody) return;

    // Start at 1 because Page 1 is already rendered by PHP on F5
    let currentPage = 1;
    let isLoading = false;
    let hasMore = true;
    let throttleTimeout = null;

    const loadMoreQuotes = async () => {
        if (isLoading || !hasMore) return;

        isLoading = true;
        currentPage++; // This will fetch page 2 first

        const searchInput = document.getElementById('quotes-search');
        const query = searchInput ? searchInput.value : '';
        
        try {
            const response = await fetch(`${window.APP_CONFIG?.baseUrl}api/quotes?page=${currentPage}&q=${encodeURIComponent(query)}`);
            const result = await response.json();

            if (result.success && result.data && result.data.length > 0) {
                const rowsHtml = result.data.map(item => item.rowHtml).join('');
                
                tableBody.insertAdjacentHTML('beforeend', rowsHtml);
                
                const countEl = document.getElementById('quotes-count');
                if (countEl && result.meta) {
                    const currentCount = tableBody.querySelectorAll('tr:not(.empty-state-row)').length;
                    countEl.textContent = `Showing ${currentCount} of ${result.meta.total} quotes`;
                }

                // Check if we've reached the end
                hasMore = result.data.length > 0 && result.meta.total > tableBody.querySelectorAll('tr').length;
            } else {
                hasMore = false;
            }
        } catch (error) {
            console.error("Infinite scroll error for quotes:", error);
            currentPage--; 
        } finally {
            isLoading = false;
        }
    };

    const handleScroll = () => {
        if (throttleTimeout) return;

        throttleTimeout = setTimeout(() => {
            throttleTimeout = null;
            
            // GUARD: If the page content is shorter than the window, 
            // don't trigger unless we are actually scrolling down.
            if (document.documentElement.scrollHeight <= window.innerHeight) return;

            const scrollBottom = window.innerHeight + window.scrollY;
            const threshold = document.documentElement.scrollHeight - 400;

            if (scrollBottom >= threshold) {
                loadMoreQuotes();
            }
        }, 200);
    };

    window.addEventListener('scroll', handleScroll);
}