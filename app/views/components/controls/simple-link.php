<?php 


namespace Views;
use Core\SingleFileView;

class SimpleLink extends SingleFileView 
{
    public $label;
    public $url;

    public function __construct($label, $url){
        $this->label = $label;
        $this->url = $url;
    }

}

__halt_compiler();
?>

<a href="<?= $this->url ?>" class="simple-link">[<?= $this->label ?>]</a>