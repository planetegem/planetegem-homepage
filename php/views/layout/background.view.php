<figure id="background">
    <canvas id="fake-3D-background"></canvas>
    <img id="background-planet" src="/resources/planetegem-logo-large.webp">
    <span id="fps-counter" class="desktop"></span>
    <span id="animation-toggle" class="desktop">
        <?= file_get_contents('resources/icons/eye_icon.svg') ?>
    </span>

    <script src="/js/background/fake-3D-background-minified.js"></script>
    <script src="/js/loader.js"></script>
    <script src="/js/terrain-viewer.js" defer></script>
</figure>