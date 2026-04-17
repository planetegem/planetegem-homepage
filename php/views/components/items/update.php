<?php

class Update extends View
{

    public $title;
    public $timestamp;
    public $description;
    public $medium;
    public $tags;
    public $links;

    public function __construct($item)
    {
        parent::__construct();

        $this->title = $item->title;
        $this->timestamp = date_parser($item->created_at);

        $this->description = $item->description;

        if (property_exists($item, 'media'))
            $this->medium = medium_factory($item->media);

        if (property_exists($item, 'links'))
            $this->links = array_map(function ($link) {
                return new ParsedLink($link);
            }, $item->links);

        $this->tags = assemble_tags($item);
    }
}

?>