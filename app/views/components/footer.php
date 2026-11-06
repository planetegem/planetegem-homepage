<?php

namespace Views;
use Core\SingleFileView;

class Footer extends SingleFileView
{}

__halt_compiler();
?>

<footer id="page-footer" class="closed">
    <p>
        &copy; <?= date('Y') ?> Niels Van Damme | 
        <a href="mailto:info@planetegem.be">info@planetegem.be</a> | 
        <a href="https://www.instagram.com/planetegem/" target="_blank" rel="noopener noreferrer">www.instagram.com/planetegem</a>
        <br> 
        &#9825; <a href="http://www.pecten.be" target="_blank" rel="noopener noreferrer">www.pecten.be</a> &#9825;
        <a href="http://www.heetfruit.be" target="_blank" rel="noopener noreferrer">www.heetfruit.be</a> &#9825;
    </p>
</footer>