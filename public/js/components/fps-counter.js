// Simple FPS counter to add a bit of personality to a page
export default class FpsCounter {
    constructor(id) {
        this.span = document.getElementById(id);
        this.startTime = performance.now();
        this.framesPassed = 0;

        if (!this.span) {
            console.warn("FPS-counter id not found");
            return;
        }
        this.refresh();
    }

    refresh() {
        this.framesPassed++;
        let elapsed = performance.now() - this.startTime;

        if (elapsed > 200) {
            // Calculate current fps, show on screen, then reset everything
            let fps = this.framesPassed * (1000 / elapsed);
            this.span.innerText = fps.toFixed(2) + " fps";
            this.framesPassed = 0;
            this.startTime = performance.now();
        }
        requestAnimationFrame(() => this.refresh());
    }
}