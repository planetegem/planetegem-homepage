<?php

namespace Views;

use Core\View;

class HomePage extends View
{
    public $items = [];
    public $pager;
    
    public function __construct($inventory)
    {
        parent::__construct();

        foreach ($inventory->result as $item) {
            $this->items[$item->id] = new Item($item);
        }

        if ($inventory->last_page > 1) {
            $this->pager = new Pager($inventory->count, $inventory->current_page, $inventory->last_page, $inventory->per_page);
        }
    }
}

?>