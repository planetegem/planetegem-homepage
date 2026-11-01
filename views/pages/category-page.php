<?php

namespace Views;
use Core\DocumentView;

class CategoryPage extends DocumentView
{
    public array $items = [];
    public ?PageHead $head = null;
    
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