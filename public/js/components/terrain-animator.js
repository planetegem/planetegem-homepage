import { orchestrator } from "./orchestrator.js";

export default class TerrainAnimator {

    constructor(background, planet) {
        // Signals when the animation is running (to know if toggle means start or stop)
        this.animationRunning = false;

        // Current phase of the animation (what's happening?!)
        this.animationPhase = "loading";

        // Check if this is the start of the session or not
        const newSession = !sessionStorage.getItem('session_started');
        if (newSession) sessionStorage.setItem('session_started', 'true');

        // Animation variables
        this.previousTerrainFrame = performance.now();
        this.elapsedTime = 0;
        this.easeInOutDuration = 500;
        this.minLoadTime = newSession ? 500 : 0;
        this.planetAscendingDuration = newSession ? 4000 : 1500;
        this.baseVelocity = newSession ? 0.0025 : 0.01;

        // Establish the background
        this.background = background;
        this.backgroundPlanet = document.getElementById(planet);
        this.otherElements = document.querySelectorAll("body > :not(#background)");

        // Button to toggle the animation on and off
        document.getElementById("animation-toggle")?.addEventListener("click", () => this.toggle());

        // Orchestrator setup
        document.body.classList.add("stop-scroll", "animation-running");
        orchestrator.interactable(false);

        orchestrator.subscribe("loading", (data) => {
            this.animationPhase = data.newState ? "loading" : "loading-complete";
            this.elapsedTime = 0;
            this.previousTerrainFrame = performance.now();
        });

        // Start running the initial loop
        requestAnimationFrame(() => this.animate());
    }

    // Toggle method to be called when stopping or starting animation
    toggle() {
        // Prevent toggle spam
        if (this.animationPhase == "starting" || this.animationPhase == "stopping") return;

        // The toggle
        this.animationRunning = !this.animationRunning;

        // Fork to the correct method depending on state
        // Fork 1: animation is starting
        if (this.animationRunning) {
            orchestrator.interactable(false);
            document.body.classList.add("stop-scroll", "animation-running");
            this.otherElements.forEach((element) => {
                element.style.opacity = 0;
            });
            this.animationPhase = "starting";
            this.elapsedTime = 0;
            this.previousTerrainFrame = performance.now();

        } else {
            this.animationPhase = "stopping";
            this.elapsedTime = 0;
            this.previousTerrainFrame = performance.now();
        }

        requestAnimationFrame(() => this.animate());
    }

    animate() {
        if (this.animationPhase == "stopped") return;

        const now = performance.now();
        const delta = now - this.previousTerrainFrame;
        this.previousTerrainFrame = now;

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
                this.elapsedTime += delta;
                progress = this.elapsedTime / this.easeInOutDuration;

                if (progress <= 1) {
                    velocity *= Math.pow(1 - progress, 3);
                } else {
                    this.animationPhase = "stopped";
                    orchestrator.interactable(true);
                    document.body.classList.remove("stop-scroll", "animation-running");
                    this.otherElements.forEach((element) => {
                        element.style.opacity = 1;
                    });
                }
                break;

            case "loading":
                this.elapsedTime += delta;
                break;

            case "loading-complete":
                this.elapsedTime += delta;

                // Planet starts ascending
                progress = (this.elapsedTime - this.minLoadTime) / this.planetAscendingDuration;

                let easeOut = Math.pow(1 - Math.max((progress - 0.5) * 2, 0), 3);
                velocity *= easeOut;


                if (progress <= 1) {
                    this.backgroundPlanet.style.transform = `translateY(${(easeOut) * 100}vh)`;
                } else {
                    // End of the animation
                    this.backgroundPlanet.style.transform = `translateY(0vh)`;
                    this.animationPhase = "stopped";
                    orchestrator.interactable(true);
                    document.body.classList.remove("stop-scroll", "animation-running");
                    this.elapsedTime = 0;
                }
                break;
        }

        this.background.changeOffset(delta * velocity);
        requestAnimationFrame(() => this.animate());
    }
}