<?php

namespace Views;

use Core\DocumentView;

class HomePage extends DocumentView
{
    public array $items = [];
    public ?Pager $pager = null;
    
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