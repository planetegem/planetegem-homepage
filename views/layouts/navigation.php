<?php 

namespace Views;
use Core\View;

class Navigation extends View {

    public $filter_menu;
    public $highlights_menu;

    public function __construct(){
        parent::__construct();

        $this->filter_menu = new FilterMenu();   
        $this->highlights_menu = new HighlightsMenu(); 
    }
}

?>