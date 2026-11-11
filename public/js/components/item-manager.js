import { orchestrator } from "./orchestrator.js";

export default class ItemManager {
    constructor() {
        this.hiddenItems = new Set(document.querySelectorAll(".hidden"));
        this.interactionAllowed = false;

        // Use IntersectionObserver to check if items are visible
        this.observer = new IntersectionObserver(
            (items) => this.handleIntersection(items),
            { rootMargin: "0px 0px -2.5% 0px" } // Triggers at 97.5% of viewport height
        );

        // Apply the observer
        this.hiddenItems.forEach((item) => this.observer.observe(item));

        // Subscribe to orchestrator
        orchestrator.subscribe("interactable", (data) => {
            this.interactionAllowed = data.newState;
            if (data.newState) {
                this.revealItems(500);
            }
        });
    }

    // Check if items are intersecting with screen on scroll
    handleIntersection(items) {
        if (!this.interactionAllowed) return;

        items.forEach((item) => {
            if (item.isIntersecting) {
                this.revealItem(item.target);
            }
        });
    }

    // Item is revealed when in viewport
    revealItem(element) {
        element.classList.remove("hidden");
        element.classList.add("visible");

        // Stop observing this element once revealed
        this.observer.unobserve(element);
        this.hiddenItems.delete(element);
    }

    // Staggered reveal, not reliant on scroll
    revealItems(delay = 0) {
        if (!this.interactionAllowed || this.hiddenItems.size === 0) return;

        // Find elements currently inside the viewport threshold
        const viewportThreshold = window.innerHeight * 0.975;

        // 1. Batch ALL reads first
        const itemsArray = Array.from(this.hiddenItems);
        const rects = itemsArray.map(item => ({
            item,
            top: item.getBoundingClientRect().top
        }));

        // 2. Filter using the cached measurements
        const itemsToReveal = rects
            .filter(entry => entry.top < viewportThreshold)
            .map(entry => entry.item);

        // 3. Schedule the reveals (WRITES)
        itemsToReveal.forEach((item, index) => {
            if (delay > 0) {
                setTimeout(() => {
                    requestAnimationFrame(() => this.revealItem(item));
                }, index * delay);
            } else {
                requestAnimationFrame(() => this.revealItem(item));
            }
        });
    }
}