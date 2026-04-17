// State variables
let interactionBlocked = true,
    loadingComplete = false,
    animationFinished = false;

// Elements
const background = new Background(false);
background.fillColor = "white";
background.strokeWidth = 2;
background.strokeColor = "#05954886";
const backgroundPlanet = document.getElementById("background-planet");

// Load trigger
window.addEventListener("load", () => {
    loadingComplete = true;
});

// Variables for animation
let previousFrame = Date.now();
let timeElapsed = 0;

// Track if it's a new session or not
const newSession = !sessionStorage.getItem('session_started');
if (newSession) sessionStorage.setItem('session_started', 'true');

// Settings for animation
let durationPhase1, durationPhase2, baseVelocity;
if (newSession) {
    durationPhase1 = 500;
    durationPhase2 = 2500;
    baseVelocity = 0.0025;
} else {
    durationPhase1 = 100;
    durationPhase2 = 1250;
    baseVelocity = 0.0025;
}

// Actual animation
function loadingAnimation() {
    if (animationFinished) return;

    let delta = Date.now() - previousFrame;
    timeElapsed += delta;
    previousFrame = Date.now();

    if (!loadingComplete && timeElapsed <= durationPhase1) {
        // Phase 1: move the background, don't touch the planet
        background.changeOffset(delta * baseVelocity);
    } else {
        // Phase 2: Start raising planet
        let progress = (timeElapsed - durationPhase1) / durationPhase2,
            easeOut = 1 - Math.pow(1 - progress, 3),
            velocity = baseVelocity * (Math.pow(1 - Math.max((progress - 0.5) * 2, 0), 3));

        if (progress <= 1) {
            backgroundPlanet.style.transform = `translateY(${(1 - easeOut) * 60}vh)`;
            background.changeOffset(delta * velocity);
        }
        if (progress > 1) {
            // End of the animation
            backgroundPlanet.style.transform = `translateY(0vh)`;
            animationFinished = true;
            setTimeout(() => {
                interactionBlocked = false;
                document.body.classList.remove("stop-scroll");
                revealItems(500);
                showNav();
            }, 100);
        }
    }
    requestAnimationFrame(loadingAnimation);
}
document.body.classList.add("stop-scroll");
loadingAnimation();

// FPS counter
let startTimeForFps = Date.now(),
    framesPassed = 0;

function refreshFrame() {
    framesPassed++;
    let elapsed = Date.now() - startTimeForFps;

    if (elapsed > 200) {
        // Calculate current fps, show on screen, then reset everything
        let fps = framesPassed * (1000 / elapsed);
        document.getElementById("fps-counter").innerHTML = fps.toFixed(2) + " fps";
        framesPassed = 0;
        startTimeForFps = Date.now();
    }
    requestAnimationFrame(refreshFrame);
}
refreshFrame();
