<?php

class Error404Page extends View
{
    public $head;
        
    public function __construct()
    {
        parent::__construct();

        $this->head = new PageHead();

    }
}

?>