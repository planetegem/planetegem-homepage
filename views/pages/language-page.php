<?php

namespace Views;
use Core\DocumentView;

class LanguagePage extends DocumentView
{
    public array $items = [];
    public ?PageHead $head = null;
    
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