<?php 

namespace Views;
use Core\SingleFileView;

class Subtitle extends SingleFileView {
    public function __construct(
        public string $title,
    ) {
    }
}

__halt_compiler();
?>

<header class="hidden floater inventory-subtitle">
    <h3><?= $this->title ?></h3>
</header>