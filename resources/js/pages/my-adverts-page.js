// /resources/js/pages/my-adverts-page.js

import { AnimationEngine } from '../utils/animations.js';
import { initAdvertsModal } from '../modals/adverts-modal.js';
import { initViewAdvert } from '../utils/adverts/view-advert.js';
import { initDeleteAdvert } from '../utils/adverts/delete-advert.js';
import { initAdInfiniteScroll } from '../utils/adverts/infinite-scroll-ads.js';
import { initAdSearch } from '../utils/adverts/search-ads.js';
import { AdCounter } from '../utils/adverts/ad-counter-helper.js'; // 🚀 Added

/**
 * Initialize the My Adverts page events
 */
export function init() {  
    // 1. Refresh AOS/Custom animations
    AnimationEngine.refresh();

    // 2. Initialize the Create/Edit (Application) modal logic
    initAdvertsModal();

    // 3. Initialize the detailed view modal
    initViewAdvert();

    // 4. Initialize the delete/archive functionality
    initDeleteAdvert();

    // 5. Initialize Infinite Scroll for the card grid
    initAdInfiniteScroll();

    // 6. Initialize Real-time Search
    initAdSearch();

    // 7. Initial Counter Sync 🚀
    // This counts the cards already on the page from the initial PHP load
    AdCounter.update();
}