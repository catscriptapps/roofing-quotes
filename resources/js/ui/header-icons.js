// /resources/js/ui/header-icons.js

import { loadPartial } from '../utils/spa-router.js';

// --- Map button IDs to partial URLs ---
function resolveUrl(id) {
  switch (id) {
    case 'messages-btn':
      return `${window.APP_CONFIG.baseUrl}messages`;

    case 'profile-btn':
      return `${window.APP_CONFIG.baseUrl}profile`;

    case 'history-btn':
      return `${window.APP_CONFIG.baseUrl}history`;

    case 'settings-btn':
      return `${window.APP_CONFIG.baseUrl}settings`;

    default:
      return null;
  }
}

// --- Attach click events to header icons ---
export function initHeaderIcons() {
    const iconIds = ['messages-btn', 'profile-btn', 'history-btn', 'settings-btn'];

    iconIds.forEach((id) => {
        const btn = document.getElementById(id);
        if (!btn) return;

        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const url = resolveUrl(id);
            if (url) {
                if (url === window.location.pathname) return;
                loadPartial(url);
                document.title = `${btn.dataset.tooltip} | ${window.APP_CONFIG?.appName || 'App'}`;
            }
        });
    });
}
