<?php

namespace Views;
use Core\View;

class CategoryPage extends View
{
    public $items = [];
    public $head;
    
    public function __construct($category)
    {
        parent::__construct();

        $this->head = new PageHead();
        $meta_title = $category->meta_title ?? $category->title;
        if ($meta_title) $this->head->title = $meta_title;
        $meta_description = $category->meta_description ?? $category->description;
        if ($meta_description) $this->head->description = $meta_description;

        foreach ($category->items as $item) {
            $this->items[$item->id] = new Item($item);
        }
    }
}

?>