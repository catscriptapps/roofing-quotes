// /resources/js/utils/mentors/view-mentor.js

import { ViewContentMapper } from './view-content-mapper.js';
import { modalDetailOwner } from "../../ui/modal-detail-owner.js";

/**
 * Handles opening the Mentor Profile Modal and mapping data
 */
export function initViewMentor() {
    if (window._viewMentorListenerAttached) return;

    document.addEventListener('click', (e) => {
        const trigger = e.target.closest('.view-mentor-trigger');
        if (!trigger) return;

        // 2. STOP if clicking an action button. Add .connect-mentor-trigger to the list!
        if (e.target.closest('.edit-mentor-btn, .delete-mentor-btn, .dropdown, .ignore-click, .connect-mentor-trigger')) {
            return; 
        }

        const data = trigger.dataset;
        const modal = document.getElementById('view-mentor-modal');
        
        if (!modal) {
            console.error("View Mentor Modal element not found in DOM.");
            return;
        }

        // 3. Map professional data (Headline, Bio, Skills, etc.)
        ViewContentMapper.mapAll(data);
        
        // 4. Map owner details (Avatar, Name)
        modalDetailOwner('mentor', data);
        
        // 5. Set reference ID for connection actions
        modal.dataset.mentorId = data.id || data.encodedId; 

        // 6. Reveal with Animation
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; 
    });

    // Handle close buttons and backdrop clicks
    ViewContentMapper.initUIBehaviors();

    window._viewMentorListenerAttached = true;
}