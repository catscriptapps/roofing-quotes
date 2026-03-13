// /resources/js/utils/home/home-events.js

import { AnimationEngine } from "../animations.js";

export function initHomeEvents() {
    // Fire the AOS refresh to catch the new content
    AnimationEngine.refresh();
}