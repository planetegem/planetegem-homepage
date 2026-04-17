<?php 

class SimpleLink extends View 
{
    public $label;
    public $url;

    public function __construct($label, $url){
        parent::__construct();

        $this->label = $label;
        $this->url = $url;
    }

}


?>