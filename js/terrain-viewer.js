const animationToggle = document.getElementById("animation-toggle");
animationToggle.addEventListener("click", () => toggleAnimation());

let animationRunning = false;

function toggleAnimation() {
    if (animationPhase == "starting" || animationPhase == "stopping") return;

    animationRunning = !animationRunning;

    if (animationRunning) startAnimation();
    if (!animationRunning) stopAnimation();
}

function startAnimation() {
    interactionBlocked = true;
    document.body.classList.add("stop-scroll");

    const allOtherElements = document.querySelectorAll("body > :not(#background");
    allOtherElements.forEach((element) => {
        element.style.opacity = 0;
    });
    animationPhase = "starting";

    setTimeout(() => {
        previousTerrainFrame = Date.now();
        timeElapsedDuringAnimation = 0;
        requestAnimationFrame(animateBackground);
    }, 200);

}

function stopAnimation() {
    animationPhase = "stopping";
    timeElapsedDuringAnimation = 0;
}

let previousTerrainFrame, timeElapsedDuringAnimation, animationPhase;
const easeInOutDuration = 500;

function animateBackground() {
    if (animationPhase == "stopped") return;

    let delta = Date.now() - previousTerrainFrame;
    previousTerrainFrame = Date.now();

    let velocity = baseVelocity, progress;

    switch (animationPhase) {
        case "starting":
            timeElapsedDuringAnimation += delta;

            progress = timeElapsedDuringAnimation / easeInOutDuration;
            if (progress <= 1) {
                velocity *= Math.pow(progress, 3);
            } else {
                animationPhase = "running";
            }
            break;

        case "stopping":
            timeElapsedDuringAnimation += delta;

            progress = timeElapsedDuringAnimation / easeInOutDuration;
            if (progress <= 1) {
                velocity *= Math.pow(1 - progress, 3);
            } else {
                animationPhase = "stopped";
                setTimeout(() => {
                    interactionBlocked = false;
                    document.body.classList.remove("stop-scroll");
                    const allOtherElements = document.querySelectorAll("body > :not(#background");
                    allOtherElements.forEach((element) => {
                        element.style.opacity = 1;
                    });
                }, 0);
            }
            break;
    }
    background.changeOffset(delta * velocity);
    requestAnimationFrame(animateBackground);
}

