<?php

namespace Views;
use Core\View;

class ItemPage extends View
{
    public $head;
    public $mainItem;

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

        $this->mainItem = new Item($item, "detail");

        $updates = $item->relationships ?? [];
        $this->updates = array_map(function ($relationship) {
            if ($relationship->relationship->label == 'parent_of')
                return new Update($relationship->item);
        }, $updates);
    }
}

?>