// /resources/js/utils/social-feed/follow-actions.js

import { refreshFeed } from "./refresh-feed.js";
import { updateProfileStats } from "./profile-stats.js";

/**
 * Handle Follow/Unfollow button clicks in the sidebar via delegation
 */
export function initFollowActions() {
    const container = document.querySelector('#suggested-follows');
    if (!container) return;

    container.addEventListener('click', async (e) => {
        const btn = e.target.closest('.follow-toggle-btn');
        if (!btn) return;

        const userId = btn.getAttribute('data-user-id');
        const baseUrl = window.APP_CONFIG?.baseUrl || '/';

        // UI Feedback: Disable and show loading
        btn.disabled = true;
        const originalText = btn.innerText;
        btn.innerText = '...';

        try {
            const response = await fetch(`${baseUrl}api/social-relations`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ following_id: userId })
            });

            const result = await response.json();

            if (result.success) {
                const row = btn.closest('.group');
                row.classList.add('opacity-0', 'translate-x-4', 'transition-all', 'duration-300');
                
                setTimeout(() => {
                    row.remove();
                    // 🍊 Refresh the feed to show the new person's posts!
                    refreshFeed();
                    // 🍊 Update the stats (we can call a function to refresh the count UI here)
                    updateProfileStats(); 
                }, 300);
            } else {
                btn.disabled = false;
                btn.innerText = originalText;
            }
        } catch (err) {
            console.error('Follow error:', err);
            btn.disabled = false;
            btn.innerText = originalText;
        }
    });
}
