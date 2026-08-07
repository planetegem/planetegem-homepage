<?php

namespace Views;
use Core\View;

class Checkbox extends View
{
   public $value;
   public $name;
   public $label;
   public $checked;

    public function __construct($value, $name, $label, $checked = false){
        parent::__construct();

        $this->value = $value;
        $this->name = $name;
        $this->label = $label;
        $this->checked = $checked;
    }
}

?>