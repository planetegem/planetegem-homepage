<?php

namespace Views;
use Core\SingleFileView;

class HighlightedItem extends SingleFileView
{
   public $title;
   public $description;
   public $url;
   public $target;

    public function __construct($title, $description, $url){

        $this->title = $title;
        $this->description = $description;
        $this->url = $url;

        // Parse url: internal links target _self, external links open new tab
        $parsedUrl = parse_url($url);
        $this->target = empty($parsedUrl['host']) ? '_self' : '_blank';
    }
}

__halt_compiler();
?>

<a class="highlight" href="<?= $this->url ?>" target="<?= $this->target ?>">
    <header>
        <?= file_get_contents('resources/icons/triangle.svg') ?>
        <p>
            <?= $this->title ?>
        </p>
    </header>
    <main>
        <p>
            <?= $this->description ?>
        </p>
    </main>
</a>