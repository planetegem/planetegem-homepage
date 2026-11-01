<?php

namespace Views;
use Core\SingleFileView;

class ReturnButton extends SingleFileView
{
    public function __construct(){}
}

__halt_compiler();
?>

<a href="/">
    <span>Return to Planetegem</span>
    <?= file_get_contents('resources/icons/return_arrow.svg') ?>
</a>