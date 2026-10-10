import { orchestrator } from "./orchestrator.js";

export default class ItemManager {
    constructor() {
        this.hiddenItems = Array.from(document.querySelectorAll(".item.hidden, .pager.hidden"));
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
        this.hiddenItems = this.hiddenItems.filter((item) => item !== element);
    }

    // Staggered reveal, not reliant on scroll
    revealItems(delay = 0) {
        if (!this.interactionAllowed || this.hiddenItems.length === 0) return;

        // Find elements currently inside the viewport threshold
        const viewportThreshold = window.innerHeight * 0.975;
        const itemsToReveal = this.hiddenItems.filter((item) => {
            const yPos = item.getBoundingClientRect().top;
            return yPos < viewportThreshold;
        });

        // Reveal matching elements using match index (not global index)
        itemsToReveal.forEach((item, index) => {
            if (delay > 0) {
                setTimeout(() => this.revealItem(item), index * delay);
            } else {
                this.revealItem(item);
            }
        });
    }
}