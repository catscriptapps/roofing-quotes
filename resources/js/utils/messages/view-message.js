// /resources/js/utils/messages/view-message.js

export function initViewMessage(mainContainer) {
    const drawer = document.getElementById('message-drawer');
    const backdrop = document.getElementById('drawer-backdrop');
    const panel = document.getElementById('drawer-panel');
    const closeBtn = document.getElementById('close-drawer');
    const drawerBody = document.getElementById('drawer-body');
    const drawerSubject = document.getElementById('drawer-subject');

    const toggleDrawer = (isOpen) => {
        if (isOpen) {
            drawer.classList.remove('invisible');
            setTimeout(() => {
                backdrop.classList.replace('opacity-0', 'opacity-100');
                panel.classList.replace('translate-x-full', 'translate-x-0');
            }, 10);
        } else {
            backdrop.classList.replace('opacity-100', 'opacity-0');
            panel.classList.replace('translate-x-0', 'translate-x-full');
            setTimeout(() => drawer.classList.add('invisible'), 500);
        }
    };

    mainContainer.addEventListener('click', async (e) => {
        const row = e.target.closest('[data-action="open-messages"]');
        if (!row || e.target.closest('button')) return;

        const messageId = row.dataset.messagesId;
        toggleDrawer(true);
        drawerBody.innerHTML = '<div class="animate-pulse space-y-4 pt-10"><div class="h-4 bg-gray-100 rounded w-3/4"></div><div class="h-4 bg-gray-100 rounded"></div><div class="h-4 bg-gray-100 rounded w-5/6"></div></div>';

        try {
            const base = window.APP_CONFIG?.baseUrl ?? '';
            const response = await fetch(`${base}api/messages?id=${messageId}`);
            const data = await response.json();

            if (data.success) {
                drawerSubject.textContent = data.subject || 'No Subject';

                const displayEmail = data.email && data.email !== 'null' ? data.email : 'System Notification';

                const headerHtml = `
                    <div class="border-b dark:border-gray-800 pb-4 mb-6">
                        <div class="text-[10px] text-primary-600 font-bold uppercase tracking-widest mb-2">Message From</div>
                        <div class="text-sm font-bold text-gray-900 dark:text-white">${data.name}</div>
                        <div class="text-xs text-gray-500">${displayEmail} • ${data.date}</div>
                    </div>
                `;

                const bodyContainer = document.createElement('div');
                bodyContainer.className = "text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-wrap break-words text-left";
                bodyContainer.textContent = data.body.trim();

                drawerBody.innerHTML = headerHtml;
                drawerBody.appendChild(bodyContainer);

                // 💎 PERMISSION CHECK: Only show buttons if user is the receiver AND status is pending
                if (data.is_handshake) {
                    if (data.can_action) {
                        const actionsDiv = document.createElement('div');
                        actionsDiv.className = "mt-8 p-6 bg-slate-900 rounded-[2rem] border border-white/5 shadow-xl";
                        actionsDiv.id = `handshake-actions-${data.handshake_id}`;
                        
                        actionsDiv.innerHTML = `
                            <h5 class="text-white font-black uppercase tracking-widest text-[10px] mb-4 opacity-70">Expert Handshake Request</h5>
                            <div class="flex flex-wrap gap-3">
                                <button class="handshake-btn px-6 py-3 bg-primary-500 hover:bg-primary-600 text-white font-black rounded-2xl text-sm transition-all active:scale-95 shadow-lg shadow-primary-500/20"
                                    data-action="accepted" data-id="${data.handshake_id}">
                                    Accept Connection
                                </button>
                                <button class="handshake-btn px-6 py-3 bg-white/10 hover:bg-white/20 text-gray-300 font-black rounded-2xl text-sm transition-all active:scale-95"
                                    data-action="declined" data-id="${data.handshake_id}">
                                    Decline
                                </button>
                            </div>
                        `;
                        drawerBody.appendChild(actionsDiv);
                    } else {
                        // Show read-only status (Especially for the Sender)
                        const statusDiv = document.createElement('div');
                        statusDiv.className = "mt-6 p-4 bg-gray-100 dark:bg-white/5 rounded-2xl text-[10px] font-black text-center uppercase tracking-widest text-gray-500";
                        statusDiv.textContent = `Handshake Status: ${data.handshake_status}`;
                        drawerBody.appendChild(statusDiv);
                    }
                }

                // 💎 SENDER CHECK: Only update the "Unread Dot" UI if the user is the RECEIVER
                if (!data.is_sender) {
                    const dot = row.querySelector('.rounded-full');
                    if (dot && dot.classList.contains('bg-primary-600')) {
                        dot.classList.replace('bg-primary-600', 'bg-transparent');
                        dot.classList.remove('shadow-[0_0_8px_rgba(139,92,246,0.5)]');
                        
                        const remaining = mainContainer.querySelectorAll('.bg-primary-600').length;
                        if (remaining === 0) {
                            document.querySelector('aside nav span.bg-red-500')?.remove();
                        }
                    }
                }
            }
        } catch (error) {
            drawerBody.innerHTML = '<p class="text-red-500 p-6">Failed to load message content.</p>';
        }
    });

    drawerBody.addEventListener('click', async (e) => {
        const btn = e.target.closest('.handshake-btn');
        if (!btn) return;

        const { id, action } = btn.dataset;
        const container = document.getElementById(`handshake-actions-${id}`);
        
        btn.disabled = true;
        btn.innerHTML = '<span class="animate-pulse">Processing...</span>';

        try {
            const base = window.APP_CONFIG?.baseUrl ?? '';
            const response = await fetch(`${base}api/mentors-action`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ request_id: id, action: action })
            });

            const result = await response.json();

            if (result.success) {
                container.innerHTML = `
                    <div class="flex items-center gap-3 text-primary-500 font-black p-4 bg-primary-500/10 rounded-2xl border border-primary-500/20 text-sm">
                        <i class="bi bi-check2-all text-xl"></i>
                        HANDSHAKE ${action.toUpperCase()}
                    </div>`;
                
                // Refresh table status
                const row = mainContainer.querySelector(`[data-messages-id="${id}"]`);
                if (row) {
                    const badge = row.querySelector('.inline-flex');
                    if (badge) badge.textContent = `Request ${action}`;
                }
            }
        } catch (error) {
            console.error('Handshake error:', error);
            btn.disabled = false;
            btn.textContent = 'Error - Try Again';
        }
    });

    closeBtn?.addEventListener('click', () => toggleDrawer(false));
    backdrop?.addEventListener('click', () => toggleDrawer(false));
}