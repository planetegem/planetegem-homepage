<?php 

class Tag extends View 
{
    public $label;
    public $link;



    public function __construct($label, $link){
        parent::__construct();

        $this->label = $label;
        $this->link = $link;
    }

}


?>