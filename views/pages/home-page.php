<?php

namespace Views;

use Core\View;

class HomePage extends View
{
    public $items = [];
    
    public function __construct($inventory)
    {
        parent::__construct();

        foreach ($inventory->result as $item) {
            $this->items[$item->id] = new Item($item);
        }
    }
}

?>