<?php

class LanguagePage extends View
{
    public $items = [];
    public $head;
    
    public function __construct($language)
    {
        parent::__construct();

        $this->head = new PageHead();

        foreach ($language->items as $item) {
            $this->items[$item->id] = new Item($item);
        }
    }
}

?>