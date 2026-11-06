<?php

namespace Views;
use Core\SingleFileView;

class Background extends SingleFileView
{
}

__halt_compiler();       
?>

<figure id="background">
    <canvas id="fake-3D-background"></canvas>
    <img 
        id="background-planet" 
        src="/resources/planetegem-logo.webp" 
        srcset="/resources/planetegem-logo-480.webp 480w,
                 /resources/planetegem-logo-800.webp 800w,
                 /resources/planetegem-logo.webp 1000w" 
        sizes="100vw" 
        alt="The green planet looming large"
        loading="eager" 
        fetchpriority="high">
    <span id="fps-counter" class="desktop"></span>
    <span id="animation-toggle" class="desktop">
        <?= file_get_contents('resources/icons/eye_icon.svg') ?>
    </span>
</figure>