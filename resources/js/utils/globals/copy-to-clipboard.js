// /resources/js/utils/globals/copy-to-clipboard.js


/**
 * Global Copy-to-Clipboard Utility
 */
export function copyToClipboard() {
    document.addEventListener('click', (e) => {
        const trigger = e.target.closest('.copy-to-clipboard');
        if (!trigger) return;

        const code = trigger.getAttribute('data-code');
        if (!code || code === '----') return;

        const handleSuccess = () => {
            const originalText = trigger.textContent;
            const originalBg = trigger.className;

            trigger.textContent = 'COPIED!';
            trigger.classList.add('!text-green-600', '!border-green-500', 'bg-green-50');

            setTimeout(() => {
                trigger.textContent = originalText;
                trigger.className = originalBg;
            }, 1500);
        };

        // 1. Try Modern Clipboard API (Requires HTTPS/Localhost)
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(code)
                .then(handleSuccess)
                .catch(err => console.error('Modern copy failed:', err));
        } else {
            // 2. Fallback: Create a hidden textarea for non-secure contexts
            try {
                const textArea = document.createElement("textarea");
                textArea.value = code;
                
                // Ensure it's not visible or interfering with layout
                textArea.style.position = "fixed";
                textArea.style.left = "-9999px";
                textArea.style.top = "0";
                document.body.appendChild(textArea);
                
                textArea.focus();
                textArea.select();
                
                const successful = document.execCommand('copy');
                document.body.removeChild(textArea);
                
                if (successful) {
                    handleSuccess();
                }
            } catch (err) {
                console.error('Fallback copy failed:', err);
            }
        }
    });
}