<?php

namespace Views;
use Core\View;

class Item extends View
{

    public $title;
    public $timestamp;
    public $description;
    public $medium;
    public $tags;
    public $links;
    public $relationship;

    public function __construct($item)
    {
        parent::__construct();

        $this->title = $item->title;
        $this->timestamp = date_parser($item->created_at);

        $this->description = $item->description;

        $media = $item->media ?? [];
        if (!empty($media)) {
            $this->medium = medium_factory($media[0]);
        }

        $links = $item->links ?? [];
        $this->links = array_map(function ($link) {
            return new ParsedLink($link);
        }, $links);

        $this->tags = assemble_tags($item);

        $relationships = $item->relationships;
        if ($relationships && !empty($relationships)) {
            foreach ($relationships as $relationship) {
                if ($relationship->relationship->label == "child_of") {
                    $relationshipLink = new SimpleLink("view all related items", "/items/{$relationship->item->slug}");
                }
                if ($relationship->relationship->label == "parent_of") {
                    $relationshipLink = new SimpleLink("view all related items", "/items/{$item->slug}");
                    break;
                }
            }
            $this->relationship = $relationshipLink;
        }

    }
}

?>