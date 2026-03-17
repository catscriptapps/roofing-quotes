// /resources/js/components/postal-code-formatter.js

/**
 * Formatter for Canadian Postal Codes matching the 'postal_code' name.
 */
export function enablePostalCodeFormatting(form) {
    if (!form) return;

    // Use name selector since IDs are dynamic with prefixes
    const postalInput = form.querySelector('input[name="postal_code"]');
    
    if (!postalInput) {
        console.warn("Postal Formatter: Could not find input[name='postal_code']");
        return;
    }

    const formatFn = (e) => {
        const input = e.target;
        let value = input.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        
        if (value.length > 3) {
            // Force the A1B 2C3 format
            value = value.slice(0, 3) + ' ' + value.slice(3, 6);
        }

        if (input.value !== value) {
            const start = input.selectionStart;
            input.value = value;
            
            // Fix cursor position if we just added a space
            if (value.charAt(start - 1) === ' ') {
                input.setSelectionRange(start + 1, start + 1);
            } else {
                input.setSelectionRange(start, start);
            }
        }
    };

    postalInput.addEventListener('input', formatFn);

    // Initial check for Edit mode
    if (postalInput.value) {
        postalInput.dispatchEvent(new Event('input'));
    }
}