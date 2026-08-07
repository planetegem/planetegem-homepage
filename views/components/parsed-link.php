<?php

namespace Views;
use Core\View;

class ParsedLink extends View
{
    public $url;
    public $target;
    public $anchor;

    public function __construct($link){
        parent::__construct();

        $this->url = $link->url;
        $this->anchor = $link->anchor;

        // Parse url: internal links target _self, external links open new tab
        $parsedUrl = parse_url($link->url);
        $this->target = empty($parsedUrl['host']) ? '_self' : '_blank';
    }
}

?>