// /resources/js/forms/quote-form.js

/**
 * Simplified Roofing Quote Form
 * Property Address, City, Locked Location, and PDF Upload
 */
export function quoteForm({
    mode = 'add',
    propertyAddress = '',
    city = '',
    postalCode = '',
    pdfFileName = '',
    buttonLabel = 'Save Quote',
    formId = 'quotes-form',
    encodedId = null
}) {
    const idPrefix = mode === 'edit' ? 'quotes-edit' : 'quotes';
    const dataEncodedIdAttr = encodedId ? `data-encoded-id="${encodedId}"` : '';
    
    const inputClasses = `
        block w-full rounded-xl 
        border border-gray-400 dark:border-gray-600 
        bg-white dark:bg-gray-900 
        text-gray-900 dark:text-white 
        placeholder:text-gray-400 
        focus:border-red-500 focus:ring-red-500 
        sm:text-sm transition-all duration-200 py-2.5 px-4
    `.replace(/\s+/g, ' ').trim();

    const labelClasses = "block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-1.5 ml-1";

    return `
    <form novalidate 
        id="${formId}" 
        class="w-full max-w-2xl mx-auto space-y-5 p-1 font-sans"
        ${dataEncodedIdAttr}>

        <div class="space-y-4">
            <div>
                <label for="${idPrefix}-property-address" class="${labelClasses}">Property Address</label>
                <input type="text" required id="${idPrefix}-property-address" name="property_address"
                    placeholder="e.g. 74 High Street" value="${propertyAddress}" class="${inputClasses}" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="${idPrefix}-city" class="${labelClasses}">City</label>
                    <input type="text" required id="${idPrefix}-city" name="city"
                        placeholder="Barrie" value="${city}" class="${inputClasses}" />
                </div>
                <div>
                    <label for="${idPrefix}-postal-code" class="${labelClasses}">Postal Code</label>
                    <input type="text" id="${idPrefix}-postal-code" name="postal_code"
                        placeholder="L4M 4Y8" value="${postalCode}" class="${inputClasses} uppercase" />
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 opacity-60">
            <div>
                <label class="${labelClasses}">Country</label>
                <select disabled class="${inputClasses} bg-gray-100 cursor-not-allowed">
                    <option>Canada</option>
                </select>
                <input type="hidden" name="country_id" value="1">
            </div>
            <div>
                <label class="${labelClasses}">Province</label>
                <select disabled class="${inputClasses} bg-gray-100 cursor-not-allowed">
                    <option>Ontario</option>
                </select>
                <input type="hidden" name="region_id" value="1">
            </div>
        </div>

        <div class="p-4 bg-red-50/50 dark:bg-red-900/10 rounded-2xl border border-red-100 dark:border-red-900/30">
            <label for="${idPrefix}-pdf" class="block text-[10px] font-black uppercase tracking-widest text-red-600 mb-2 ml-1">
                ${mode === 'edit' ? 'Update Quote PDF' : 'Attach Quote PDF'}
            </label>
            <input type="file" id="${idPrefix}-pdf" name="pdf_file" accept=".pdf"
                class="block w-full text-xs text-gray-500
                file:mr-4 file:py-2 file:px-4
                file:rounded-xl file:border-0
                file:text-xs file:font-black file:uppercase
                file:bg-red-600 file:text-white
                hover:file:bg-red-700 cursor-pointer" />
            ${pdfFileName ? `<p class="mt-2 text-[10px] text-gray-500 italic">Current file: ${pdfFileName}</p>` : ''}
        </div>

        <div class="flex items-center justify-end pt-4 border-t border-gray-100 dark:border-gray-800">
            <button type="submit" 
                class="inline-flex items-center justify-center rounded-xl bg-red-600 px-10 py-3 text-sm font-black uppercase tracking-widest text-white shadow-lg shadow-red-500/20 hover:bg-red-700 transition-all active:scale-95">
                ${buttonLabel}
            </button>
        </div>
    </form>
    `;
}