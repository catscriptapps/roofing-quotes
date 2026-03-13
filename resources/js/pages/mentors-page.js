import { AnimationEngine } from '../utils/animations';
import { initMentorsModal } from '../modals/mentors-modal.js';
import { initMentorSearch } from '../utils/mentors/search-mentors.js';
import { initViewMentor } from '../utils/mentors/view-mentor.js';
import { initDeleteMentor } from '../utils/mentors/delete-mentor.js';
import { initMentorsConnect } from '../modals/mentors-connect-modal.js';

/**
 * Initialize Mentors Hub Events
 */
export function init() {  
    // 1. Initial Load & Animation Refresh
    refreshMentorPageState();

    // 2. Persistent Features (Delegated events, so call once)
    initMentorsModal();
    initMentorSearch();
    initMentorsConnect(); // 👈 Initialize the "Message/Connect" logic once

    // 3. Initialize the delete functionality
    initDeleteMentor();
}

/**
 * Re-binds event listeners to cards (Run on load + after AJAX search)
 */
export function refreshMentorPageState() {
    AnimationEngine.refresh();

    // Re-bind View/Details listeners for the mentor cards
    initViewMentor();
}