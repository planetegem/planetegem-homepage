<?php

namespace Views;
use Core\DocumentView;

class ItemPage extends DocumentView
{
    public ?PageHead $head = null;
    public ?Item $mainItem = null;

    public array $updates = [];


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