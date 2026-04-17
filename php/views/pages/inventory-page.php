<?php

class InventoryPage extends View
{
    public $items = [];
    public $head;
    
    public function __construct($inventory)
    {
        parent::__construct();

        $this->head = new PageHead();
        foreach ($inventory->result as $item) {
            $this->items[$item->id] = new Item($item);
        }
    }
}

?>