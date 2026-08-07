<?php

namespace Views;
use Core\View;

class HighlightedItem extends View
{
   public $title;
   public $description;
   public $url;
   public $target;

    public function __construct($title, $description, $url){
        parent::__construct();

        $this->title = $title;
        $this->description = $description;
        $this->url = $url;

        // Parse url: internal links target _self, external links open new tab
        $parsedUrl = parse_url($url);
        $this->target = empty($parsedUrl['host']) ? '_self' : '_blank';
    }
}

?>