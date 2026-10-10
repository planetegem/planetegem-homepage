import { orchestrator } from "./orchestrator.js";

export default class TerrainAnimator {

    constructor(background, planet) {
        // State handlers
        // Signals when the animation is running (to know if toggle means start or stop)
        this.animationRunning = false;
        // Current phase of the animation (what's happening?!)
        this.animationPhase = "loading";

        // Check if this is the start of the session or not
        const newSession = !sessionStorage.getItem('session_started');
        if (newSession) sessionStorage.setItem('session_started', 'true');

        // Animation variables
        this.previousTerrainFrame = 0;
        this.elapsedTime = 0;
        this.easeInOutDuration = 500;
        this.minLoadTime = newSession ? 500 : 100;
        this.planetAscendingDuration = newSession ? 4000 : 2500;
        this.baseVelocity = newSession ? 0.0025 : 0.0025;

        // Establish the background
        this.background = background;
        this.backgroundPlanet = document.getElementById(planet);

        // Button to toggle the animation on and off
        const animationToggle = document.getElementById("animation-toggle");
        animationToggle.addEventListener("click", () => this.toggle());

        orchestrator.subscribe("loading", (data) => { 
            this.animationPhase = data.newState ? "loading" : "loading-complete";
        });

        // Start running the animation on construction (for loading animation)
        this.animate();
    }

    // Toggle method to be called when stopping or starting animation
    toggle() {
        // If in the process of starting or stopping already, don't toggle to avoid loop
        if (this.animationPhase == "starting" || this.animationPhase == "stopping") return;

        // The toggle
        this.animationRunning = !this.animationRunning;

        // Fork to the correct method depending on state
        // Fork 1: animation is starting
        if (this.animationRunning) {
            orchestrator.interactable(false);
            document.body.classList.add("stop-scroll");

            const allOtherElements = document.querySelectorAll("body > :not(#background");
            allOtherElements.forEach((element) => {
                element.style.opacity = 0;
            });
            this.animationPhase = "starting";

            setTimeout(() => {
                this.previousTerrainFrame = performance.now();
                this.elapsedTime = 0;
                requestAnimationFrame(() => this.animate());
            }, 200);
        };

        // Fork 2: animation is stopping
        if (!this.animationRunning) {
            this.animationPhase = "stopping";
            this.timeElapsed = 0;
        }
    }

    animate() {
        if (this.animationPhase == "stopped") return;

        let delta = performance.now() - this.previousTerrainFrame;
        this.previousTerrainFrame = performance.now();

        let velocity = this.baseVelocity, progress;

        switch (this.animationPhase) {
            case "starting":
                this.elapsedTime += delta;

                progress = this.elapsedTime / this.easeInOutDuration;
                if (progress <= 1) {
                    velocity *= Math.pow(progress, 3);
                } else {
                    this.animationPhase = "running";
                }
                break;

            case "stopping":
                this.timeElapsed += delta;

                progress = this.timeElapsed / this.easeInOutDuration;
                if (progress <= 1) {
                    velocity *= Math.pow(1 - progress, 3);
                } else {
                    this.animationPhase = "stopped";
                    setTimeout(() => {
                        orchestrator.interactable(true);
                        document.body.classList.remove("stop-scroll");
                        const allOtherElements = document.querySelectorAll("body > :not(#background");
                        allOtherElements.forEach((element) => {
                            element.style.opacity = 1;
                        });
                    }, 0);
                }
                break;

            case "loading":
                this.elapsedTime += delta;
                break;

            case "loading-complete":
                this.elapsedTime += delta;

                if (this.elapsedTime <= this.minLoadTime)
                    return;

                // Planet starts ascending
                progress = (this.elapsedTime - this.minLoadTime) / this.planetAscendingDuration;
                velocity *= Math.pow(1 - Math.max((progress - 0.5) * 2, 0), 3);

                let easeOut = 1 - Math.pow(1 - progress, 3);

                if (progress <= 1) {
                    this.backgroundPlanet.style.transform = `translateY(${(1 - easeOut) * 100}vh)`;
                }
                if (progress > 1) {
                    // End of the animation
                    this.backgroundPlanet.style.transform = `translateY(0vh)`;
                    this.animationPhase = "stopped";
                    setTimeout(() => {
                        orchestrator.interactable(true);
                        document.body.classList.remove("stop-scroll");
                        this.elapsedTime = 0;
                    }, 100);
                }
                break;
        }
        this.background.changeOffset(delta * velocity);
        requestAnimationFrame(() => this.animate());
    }

}