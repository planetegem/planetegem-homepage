<?php

namespace Views;
use Core\View;

class ItemPage extends View
{
    public $head;

    public $title;
    public $timestamp;
    public $description;
    public $medium;
    public $tags;
    public $links;

    public $updates = [];


    public function __construct($item)
    {
        parent::__construct();

        $this->head = new PageHead();
        /*
        $meta_title = $category->meta_title ?? $category->title;
        if ($meta_title) $this->head->title = $meta_title;
        $meta_description = $category->meta_description ?? $category->description;
        if ($meta_description) $this->head->description = $meta_description; */

        $this->title = $item->title;
        $this->timestamp = date_parser($item->created_at);
        $this->description = $item->description;
        if ($item->media)
            $this->medium = medium_factory($item->media);
        $this->tags = assemble_tags($item);

        $links = $item->links ?? [];
        $this->links = array_map(function ($link) {
            return new ParsedLink($link);
        }, $links);

        $updates = $item->relationships ?? [];
        $this->updates = array_map(function ($relationship) {
            if ($relationship->relationship == 'update')
                return new Update($relationship->item);
        }, $updates);
    }
}

?>