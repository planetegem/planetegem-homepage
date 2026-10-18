<?php

namespace Views;
use Core\View;

class Update extends View
{

    public $title;
    public $timestamp;
    public $description;
    public $medium;
    public $tags;
    public $links;

    public function __construct($item, $type = "lister")
    {
        parent::__construct();

        $this->title = $item->title;
        $this->timestamp = date_parser($item->created_at);

        $this->description = $item->description;

        $media = $item->media ?? [];
        if (!empty($media)) {
            $this->medium = medium_factory($media[0]);
        }

        if (property_exists($item, 'links'))
            $this->links = array_map(function ($link) {
                return new ParsedLink($link);
            }, $item->links);

        $this->tags = assemble_tags($item);
    }
}

?>