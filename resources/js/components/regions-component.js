// /resources/js/components/regions-component.js

import { fetchRegions } from "../api/regions-api.js";

/**
 * Enables dynamic loading of regions when the country select changes.
 */
export function enableDynamicRegionLoading(formId) {
    const form = document.getElementById(formId);
    if (!form) return;

    const countrySelect = form.querySelector('select[name="countryId"]');
    const regionSelect = form.querySelector('select[name="regionId"]');

    if (!countrySelect || !regionSelect) return;

    countrySelect.addEventListener('change', async () => {
        const countryId = parseInt(countrySelect.value);

        regionSelect.innerHTML = `<option value="">Loading...</option>`;
        regionSelect.disabled = true;

        try {
            const regions = await fetchRegions(countryId);
            regionSelect.innerHTML = `
                <option value="">Select Region</option>
                ${regions.map(r => `<option value="${r.id}">${r.name}</option>`).join('')}
            `;
        } catch (error) {
            console.error('Failed to reload regions:', error);
            regionSelect.innerHTML = `<option value="">Error loading regions</option>`;
        } finally {
            regionSelect.disabled = false;
        }
    });
}