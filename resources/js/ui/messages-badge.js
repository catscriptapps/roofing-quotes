// /resources/js/ui/messages-badge.js

/**
 * Polls unread messages count and updates the badge
 */
export function init() {
  const badge = document.getElementById('messages-badge');
  const btn = document.getElementById('messages-btn');

  if (!badge || !btn) return;

  async function updateBadge() {
    try {
      const res = await fetch(`${window.APP_CONFIG.baseUrl}api/messages-unread`, { cache: 'no-store' });
      const data = await res.json();

      if (data.success && data.unread_count > 0) {
        badge.textContent = data.unread_count;
        badge.classList.remove('hidden');
      } else {
        badge.classList.add('hidden');
      }
    } catch (err) {
      console.error('Failed to fetch unread messages:', err);
    }
  }

  // Initial fetch
  updateBadge();

  // Poll every 30s
  setInterval(updateBadge, 30000);
}
