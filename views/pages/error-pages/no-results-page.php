<?php

namespace Views;
use Core\View;

class NoResultsPage extends View
{
    public $head;
        
    public function __construct()
    {
        parent::__construct();

        $this->head = new PageHead();

    }
}

?>