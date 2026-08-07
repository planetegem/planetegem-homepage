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

        $media = $item->media ?? $this->FallbackMedium($item->relationships);
        if ($media){
            if ($media->type == 'none') $media = $this->FallbackMedium($item->relationships);
            $this->medium = medium_factory($media);
        }

        $links = $item->links ?? $this->FallbackLinks($item->relationships);
        $this->links = array_map(function ($link) {
            return new ParsedLink($link);
        }, $links);

        $this->tags = assemble_tags($item);

        if (isset($item->relationships) && $item->relationships) {
            if ($item->type == 'master') {
                $this->relationship = new SimpleLink("view all related items", "/items/{$item->slug}");
            } else if ($item->type == 'update') {
                foreach ($item->relationships as $relationship) {
                    if ($relationship->relationship == 'master')
                        $this->relationship = new SimpleLink("view all related items", "/items/{$relationship->item->slug}");
                }
            }
        }

    }
    private function FallbackLinks($relationships)
    {
        foreach ($relationships as $relationship) {
            if ($relationship->relationship == "master")
                return $relationship->item->links;
        }
        return [];
    }
    private function FallbackMedium($relationships)
    {
        foreach ($relationships as $relationship) {
            if ($relationship->relationship == "master")
                return $relationship->item->media;
        }
        return null;
    }

    
}

?>