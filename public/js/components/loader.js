import { orchestrator } from "./orchestrator.js";

export default class Loader {
    constructor() {
        window.addEventListener("load", () => {
            // 1. Select all custom elements on the page
            const shadowHosts = document.querySelectorAll('expandable-thumbnail, expandable-gallery');

            // 2. Collect high-priority images from all shadow roots
            const priorityImages = [];
            shadowHosts.forEach((host) => {
                if (host.shadowRoot) {
                    const imgs = host.shadowRoot.querySelectorAll('img[data-priority="high"]');
                    priorityImages.push(...imgs);
                }
            });

            console.log(`Found ${priorityImages.length} high-priority image(s) across ${shadowHosts.length} component(s).`);

            // 3. Return early if no priority images exist
            if (priorityImages.length === 0) {
                orchestrator.loaded();
                return;
            }

            // 4. Create a load promise for every image found
            const loadPromises = priorityImages.map((img) => {
                return new Promise((resolve) => {
                    // If already loaded or no src to set
                    if (img.complete && img.src) {
                        resolve();
                        return;
                    }

                    const handleLoad = () => {
                        cleanup();
                        resolve();
                    };

                    const handleError = () => {
                        cleanup();
                        console.warn(`Failed to load high-priority image: ${img.dataset.src}`);
                        resolve(); // Prevent a broken image from hanging the orchestrator
                    };

                    const cleanup = () => {
                        img.removeEventListener('load', handleLoad);
                        img.removeEventListener('error', handleError);
                    };

                    img.addEventListener('load', handleLoad);
                    img.addEventListener('error', handleError);

                    // Trigger image fetch
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                    } else {
                        resolve();
                    }
                });
            });

            // 5. Signal completion once ALL images from ALL components load
            Promise.all(loadPromises).then(() => {
                orchestrator.loaded();
            });
        });
    }
}