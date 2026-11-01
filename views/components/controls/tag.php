<?php

namespace Views;
use Core\SingleFileView;

class Tag extends SingleFileView
{
    public function __construct(public string $label, public string $link)
    {
    }
}

__halt_compiler(); 
?>

<li class="item-tag">
    <a href="<?= $this->link ?>"><?= $this->label ?></a>
</li>